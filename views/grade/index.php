<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Grade[] $grades */
/** @var app\models\Semester[] $semesters */
/** @var app\models\Semester|null $selectedSemester */
/** @var app\models\Student $student */ // ✅ Make sure $student is passed from controller

$this->title = 'My Grades';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="grade-index container mt-4">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Semester Selection Dropdown -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <strong>Select Semester</strong>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => Url::to(['grade/index']),
                'options' => ['class' => 'form-inline'],
            ]); ?>

            <div class="form-group me-2">
                <?= Html::dropDownList(
                    'semester_id',
                    $selectedSemester ? $selectedSemester->id : null,
                    \yii\helpers\ArrayHelper::map($semesters, 'id', function ($sem) {
                        return $sem->academicYear->year_name . ' - ' . $sem->name;
                    }),
                    ['class' => 'form-select', 'prompt' => 'Select Semester', 'onchange' => 'this.form.submit()']
                ) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <!-- ✅ Download Transcript Button -->
    <?php if (!empty($grades)): ?>
        <div class="mb-3">
            <?= Html::a('📄 Download Transcript', ['transcript/pdf', 'studentId' => $student->id], [
                'class' => 'btn btn-primary',
                'target' => '_blank',
            ]) ?>
        </div>
    <?php endif; ?>

    <!-- Grades Table -->
    <?php if ($grades): ?>
        <div class="card">
            <div class="card-header bg-success text-white">
                <strong>Grades for: <?= Html::encode($selectedSemester->academicYear->year_name . ' - ' . $selectedSemester->name) ?></strong>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Class</th>
                            <th>Grade</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($grades as $grade): ?>
                            <tr>
                                <td><?= Html::encode($grade->class->class_name ?? 'N/A') ?></td>
                                <td><?= Html::encode($grade->score ?? 'Pending') ?></td>
                                <td>
                                    <?= $grade->score && $grade->score < 50 ? 'Failed' : ($grade->score ? 'Passed' : 'N/A') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            No grades found for the selected semester.
        </div>
    <?php endif; ?>

</div>
