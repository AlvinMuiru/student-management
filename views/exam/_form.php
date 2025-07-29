<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Exam $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $classes */
/** @var array $semesters */
/** @var array $invigilators */
?>

<div class="exam-form container">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'class_id')->dropDownList(
        ArrayHelper::map($classes, 'id', 'class_name'),
        ['prompt' => 'Select Class']
    ) ?>

    <?= $form->field($model, 'semester_id')->dropDownList(
        ArrayHelper::map($semesters, 'id', 'name'),
        ['prompt' => 'Select Semester']
    ) ?>

    <?= $form->field($model, 'invigilator_id')->dropDownList(
        ArrayHelper::map($invigilators, 'id', 'username'), // change to 'name' if full name exists
        ['prompt' => 'Select Invigilator']
    ) ?>

    <?= $form->field($model, 'exam_date')->input('date') ?>
    <?= $form->field($model, 'start_time')->input('time') ?>
    <?= $form->field($model, 'end_time')->input('time') ?>
    <?= $form->field($model, 'venue')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
