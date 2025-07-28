<?php
use yii\helpers\Html;

/** @var $exams app\models\ExamSchedule[] */

$this->title = 'My Exam Schedule';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="exam-schedule">
    <h3><?= Html::encode($this->title) ?></h3>

    <?php if (empty($exams)): ?>
        <div class="alert alert-info">No exams scheduled for your classes.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Course</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Venue</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($exams as $exam): ?>
                    <tr>
                        <td><?= Html::encode($exam->class->class_name ?? '-') ?></td>
                        <td><?= Html::encode($exam->class->course->name ?? '-') ?></td>
                        <td><?= Yii::$app->formatter->asDate($exam->exam_date) ?></td>
                        <td><?= Html::encode($exam->start_time) ?></td>
                        <td><?= Html::encode($exam->end_time) ?></td>
                        <td><?= Html::encode($exam->venue ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
