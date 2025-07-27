<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$form = ActiveForm::begin(); ?>

<?= $form->field($model, 'class_id')->dropDownList(
    \yii\helpers\ArrayHelper::map($classes, 'id', 'class_name'),
    ['prompt' => 'Select Class']
) ?>

<?= $form->field($model, 'semester_id')->dropDownList(
    \yii\helpers\ArrayHelper::map($semesters, 'id', 'name'),
    ['prompt' => 'Select Semester']
) ?>

<?= $form->field($model, 'exam_date')->input('date') ?>
<?= $form->field($model, 'start_time')->input('time') ?>
<?= $form->field($model, 'end_time')->input('time') ?>
<?= $form->field($model, 'venue')->textInput() ?>

<div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
