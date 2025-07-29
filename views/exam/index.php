<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ExamSchedule[] $exams */

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
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Venue</th>
                    <th>Invigilator</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($exams as $index => $exam): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= Html::encode($exam->class->class_name ?? '-') ?></td>
                        <td><?= Html::encode($exam->semester->name ?? '-') ?></td>
                        <td><?= Html::encode($exam->exam_date) ?></td>
                        <td><?= Html::encode($exam->start_time) ?></td>
                        <td><?= Html::encode($exam->end_time) ?></td>
                        <td><?= Html::encode($exam->venue) ?></td>
                        <td><?= Html::encode($exam->invigilator->username) ?></td>
                        <td>
                            <?= Html::a('Update', ['update', 'id' => $exam->id], ['class' => 'btn btn-sm btn-primary']) ?>
                            <?= Html::a('Delete', ['delete', 'id' => $exam->id], [
                                'class' => 'btn btn-sm btn-danger',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this exam?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No exam schedules found.</p>
    <?php endif; ?>
</div>
