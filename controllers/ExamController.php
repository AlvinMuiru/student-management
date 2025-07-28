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
     
    public function actionMySchedule()
{
    $userId = Yii::$app->user->id;
    $student = \app\models\Students::findOne(['user_id' => $userId]);

    // 1. Check if student exists
    if (!$student) {
        throw new \yii\web\NotFoundHttpException("Student profile not found.");
    }

    // 2. Check fee clearance (adjust logic if using semester filtering)
    $hasClearedFees = \app\models\StudentFee::find()
        ->where(['student_id' => $student->id, 'status' => 'paid']) // or use constant
        ->exists();

    if (!$hasClearedFees) {
        Yii::$app->session->setFlash('error', 'You must clear your fees to view your exam schedule.');
        return $this->redirect(['dashboard/index']);
    }

    // 3. Get enrolled class IDs
    $classIds = \app\models\ClassAssignment::find()
        ->select('class_id')
        ->where(['student_id' => $student->id])
        ->column();

    // 4. Get exams for those classes
    $exams = \app\models\ExamSchedule::find()
        ->where(['class_id' => $classIds])
        ->orderBy(['exam_date' => SORT_ASC])
        ->all();

    return $this->render('my-schedule', [
        'exams' => $exams,
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
