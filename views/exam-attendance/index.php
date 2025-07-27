<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
//dd(get_class($exams[0]));

/** @var yii\web\View $this */
/** @var app\models\ExamSchedule $schedule */
/** @var app\models\Student[] $students */
/** @var app\models\ExamAttendance[] $attendances */

$this->title = 'Exam Attendance for ' . $schedule->exam_date;

?>

<div class="exam-attendance-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p><strong>Class:</strong> <?= Html::encode($schedule->class->class_name) ?> |
       <strong>Date:</strong> <?= Html::encode($schedule->exam_date) ?> |
       <strong>Venue:</strong> <?= Html::encode($schedule->venue) ?></p>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Student</th>
                <th>Reg. No.</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $index => $student): 
                $attendance = $attendances[$student->id] ?? null;
            ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= Html::encode($student->first_name . ' ' . $student->last_name) ?></td>
                    <td><?= Html::encode($student->reg_no) ?></td>
                    <td>
                        <?= $attendance ? $attendance->status : 'Not Marked' ?>
                    </td>
                    <td>
                        <?php $form = ActiveForm::begin([
                            'action' => Url::to(['exam-attendance/mark']),
                            'method' => 'post',
                            'options' => ['class' => 'form-inline']
                        ]); ?>

                        <?= Html::hiddenInput('exam_schedule_id', $schedule->id) ?>
                        <?= Html::hiddenInput('student_id', $student->id) ?>
                        <?= Html::dropDownList('status', $attendance->status ?? null, [
                            'Present' => 'Present',
                            'Absent' => 'Absent'
                        ], ['class' => 'form-control']) ?>
                        
                        <?= Html::submitButton($attendance ? 'Update' : 'Mark', ['class' => 'btn btn-primary btn-sm ml-2']) ?>

                        <?php ActiveForm::end(); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
