<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$form = ActiveForm::begin(); ?>

<?= $form->field($model, 'teacher_id')->dropDownList(
    ArrayHelper::map($teachers, 'id', function($t) {
        return $t->first_name . ' ' . $t->last_name;
    }),
    ['prompt' => 'Select Teacher']
) ?>

<div class="form-group">
    <?= Html::submitButton('Assign Invigilator', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
