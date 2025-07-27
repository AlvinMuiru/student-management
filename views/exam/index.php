<?php

/** @var yii\web\View $this */
/** @var app\models\ExamSchedule[] $exams */

use yii\helpers\Html;

$this->title = 'Exam Schedules';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="exam-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::a('Create Exam', ['create'], ['class' => 'btn btn-success']) ?></p>

    <?php if (!empty($exams)): ?>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Class</th>
                    <th>Semester</th>
                    <th>Exam Date</th>
                    <th>Time</th>
                    <th>Venue</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($exams as $index => $exam): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td>
                            <?= $exam->class ? Html::encode($exam->class->class_name) : 'N/A' ?>
                        </td>
                        <td>
                            <?= $exam->semester ? Html::encode($exam->semester->name) : 'N/A' ?>
                        </td>
                        <td><?= Html::encode($exam->exam_date) ?></td>
                        <td><?= Html::encode("{$exam->start_time} - {$exam->end_time}") ?></td>
                        <td><?= Html::encode($exam->venue) ?></td>
                        <td>
                             <?= Html::a('Record Attendance', ['exam-attendance/index', 'schedule_id' => $exam->id], ['class' => 'btn btn-success']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No exams found.</p>
    <?php endif; ?>
</div>
