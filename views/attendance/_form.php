<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Students;
use app\models\ClassModel;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Attendance */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="attendance-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'student_id')->dropDownList(
        ArrayHelper::map(Students::find()->all(), 'id', 'fullName'),
        ['prompt' => 'Select Student']
    ) ?>

    <?= $form->field($model, 'class_id')->dropDownList(
        ArrayHelper::map(ClassModel::find()->all(), 'id', 'class_name'),
        ['prompt' => 'Select Class']
    ) ?>

    <?= $form->field($model, 'date')->input('date') ?>

    <?= $form->field($model, 'status')->dropDownList(
        ['present' => 'Present', 'absent' => 'Absent'],
        ['prompt' => 'Select Status']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>