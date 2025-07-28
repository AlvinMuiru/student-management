<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\models\ExamSchedule;
use app\models\ClassModel;
use app\models\Semester;
use app\models\User;

class ExamController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // You can replace this with 'teacher', 'admin' using RBAC
                    ],
                ],
            ],
        ];
    }

 public function actionIndex()
{
    $exams = ExamSchedule::find()
        ->with(['class', 'semester']) // eager load to avoid N+1 queries
        ->all();

    return $this->render('index', [
        'exams' => $exams,
    ]);
}

   public function actionCreate()
{
    $model = new ExamSchedule();

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
        Yii::debug("✅ Saved exam with invigilator user_id: " . $model->invigilator_id);

        if (!$model->invigilator_id) {
            Yii::debug("⚠️ No invigilator_id found in POST data");
        } else {
            // 🔁 Convert user_id to teacher_id
            $teacher = \app\models\Teacher::find()->where(['user_id' => $model->invigilator_id])->one();

            if ($teacher) {
                // ✅ Check if already exists
                $exists = \app\models\ExamInvigilator::find()
                    ->where([
                        'exam_schedule_id' => $model->id,
                        'teacher_id' => $teacher->id
                    ])
                    ->exists();

                if (!$exists) {
                    $invigilator = new \app\models\ExamInvigilator();
                    $invigilator->exam_schedule_id = $model->id;
                    $invigilator->teacher_id = $teacher->id;
                    $invigilator->assigned_at = date('Y-m-d H:i:s');

                    if (!$invigilator->save()) {
                        Yii::error('❌ Failed to save ExamInvigilator: ' . print_r($invigilator->errors, true));
                        var_dump($invigilator->errors);
                        exit;
                    }
                }
            } else {
                Yii::error("❌ No teacher found with user_id = {$model->invigilator_id}");
            }
        }

        Yii::$app->session->setFlash('success', '✅ Exam created successfully.');
        return $this->redirect(['index']);
    }

    return $this->render('create', [
        'model' => $model,
        'classes' => ClassModel::find()->all(),
        'semesters' => Semester::find()->all(),
        'invigilators' => User::find()->where(['role' => 'teacher'])->all(),
    ]);
}

    public function actionView($id)
    {
        return $this->render('index', [
            'model' => $this->findModel($id),
        ]);
    }
     
    
    protected function findModel($id)
    {
        if (($model = ExamSchedule::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested exam does not exist.');
    }
}
