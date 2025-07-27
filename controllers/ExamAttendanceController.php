<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use app\models\ExamAttendance;
use app\models\ExamSchedule;
use app\models\Student;
use app\models\ClassAssignment;
use yii\helpers\ArrayHelper;

class ExamAttendanceController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'mark', 'view'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Customize with RBAC roles if needed
                    ],
                ],
            ],
        ];
    }

    /**
     * List of students for a specific exam schedule.
     */
    public function actionIndex($schedule_id)
    {
        $schedule = ExamSchedule::findOne($schedule_id);
        if (!$schedule) {
            throw new NotFoundHttpException('Exam Schedule not found.');
        }

        // Fetch enrolled students for the class
        $assignments = ClassAssignment::find()
            ->where(['class_id' => $schedule->class_id])
            ->all();

        $students = [];
        foreach ($assignments as $assignment) {
            $students[] = $assignment->student;
        }

        $attendances = ExamAttendance::find()
            ->where(['exam_schedule_id' => $schedule_id])
            ->indexBy('student_id')
            ->all();

        return $this->render('index', [
            'schedule' => $schedule,
            'students' => $students,
            'attendances' => $attendances,
        ]);
    }

    /**
     * Mark attendance for a student.
     */
    public function actionMark()
    {
        $request = Yii::$app->request;

        $exam_schedule_id = $request->post('exam_schedule_id');
        $student_id = $request->post('student_id');
        $status = $request->post('status');

        if (!$exam_schedule_id || !$student_id || !$status) {
            Yii::$app->session->setFlash('error', 'Missing data.');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $attendance = ExamAttendance::findOne([
            'exam_schedule_id' => $exam_schedule_id,
            'student_id' => $student_id,
        ]);

        if (!$attendance) {
            $attendance = new ExamAttendance([
                'exam_schedule_id' => $exam_schedule_id,
                'student_id' => $student_id,
            ]);
        }

        $attendance->status = $status;

        if ($attendance->save()) {
            Yii::$app->session->setFlash('success', 'Attendance updated.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to update attendance.');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
   public function actionCreate($schedule_id)
{
    $model = new ExamAttendance();

    $schedule = ExamSchedule::findOne($schedule_id);

    if (!$schedule) {
        throw new NotFoundHttpException('Exam schedule not found for this exam.');
    }

    $model->exam_schedule_id = $schedule->id;

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['index']);
    }

    return $this->render('create', [
        'model' => $model,
    ]);
}

}
