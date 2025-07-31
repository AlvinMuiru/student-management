<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\RetakeRequest;
use yii\data\ActiveDataProvider;
use app\models\Grade;

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

    // Admin/Teacher view of all requests
    public function actionIndex()
    {
        $query = RetakeRequest::find()
            ->joinWith(['grade.class', 'student'])
            ->orderBy(['retake_requests.created_at' => SORT_DESC]);

        // Restrict to teacher's students if user is a teacher
        if (Yii::$app->user->can('teacher') && Yii::$app->user->identity->teacher) {
            $teacherId = Yii::$app->user->identity->teacher->id;
            $query->andWhere(['classes.teacher_id' => $teacherId]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    // Reassess logic (Admin/Teacher)
    public function actionReassess($id)
    {
        $retake = RetakeRequest::findOne($id);
        if (!$retake || !$retake->grade) {
            throw new \yii\web\NotFoundHttpException("Retake request not found.");
        }

        $grade = $retake->grade;

        if (Yii::$app->request->isPost) {
            $newScore = Yii::$app->request->post('score');
            $grade->score = $newScore;
            $grade->passed = $newScore >= 40;
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

  
public function actionMyRequests()
{
    $studentId = Yii::$app->user->identity->student->id;

    $query = Grade::find()
        ->alias('g')
        ->joinWith('class c')
        ->leftJoin('retake_requests rr', 'rr.grade_id = g.id AND rr.student_id = :studentId', [':studentId' => $studentId])
        ->where(['g.student_id' => $studentId])
        ->andWhere(['<', 'g.score', 40])
        ->select([
            'g.*',
            'rr.id AS request_id',
            'rr.status AS request_status',
            'rr.created_at AS request_created_at'
        ]);

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => ['pageSize' => 10],
    ]);

    return $this->render('my-requests', [
        'dataProvider' => $dataProvider,
    ]);
}


}
