<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\RetakeRequest;
use app\models\Grade;
use yii\data\ActiveDataProvider;

class RetakeRequestController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    // View for teachers/admin
    
public function actionIndex()
{
    $query = RetakeRequest::find()->joinWith(['grade.class', 'student']);

    // Only filter if current user is a teacher
    if (Yii::$app->user->can('teacher') && isset(Yii::$app->user->identity->teacher)) {
        $teacherId = Yii::$app->user->identity->teacher->id;

        // 💡 Fix alias: use 'classes.teacher_id' instead of 'class_model.teacher_id'
        $query->andWhere(['classes.teacher_id' => $teacherId]);
    }

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
    ]);

    return $this->render('index', ['dataProvider' => $dataProvider]);
}

    // Reassess (update score)
    public function actionReassess($id)
    {
        $retake = RetakeRequest::findOne($id);
        $grade = $retake->grade;

        if (Yii::$app->request->isPost) {
            $newScore = Yii::$app->request->post('score');
            $grade->score = $newScore;
            $grade->passed = $newScore >= 50;
            if ($grade->save(false)) {
                $retake->status = 'reassessed';
                $retake->save(false);
                Yii::$app->session->setFlash('success', 'Grade reassessed successfully.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('reassess', [
            'retake' => $retake,
            'grade' => $grade,
        ]);
    }

    // Students view their own retake requests
    public function actionMyRequests()
    {
        $studentId = Yii::$app->user->identity->student->id;

        $dataProvider = new ActiveDataProvider([
            'query' => RetakeRequest::find()->where(['student_id' => $studentId]),
        ]);

        return $this->render('my-requests', ['dataProvider' => $dataProvider]);
    }
}
