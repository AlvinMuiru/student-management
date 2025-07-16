<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Courses;

/** @var yii\web\View $this */
/** @var app\models\Fee $model */
/** @var yii\widgets\ActiveForm $form */


$courses = ArrayHelper::map(Courses::find()->all(), 'id', 'name');
?>


<div class="fee-form">

    <?php $form = ActiveForm::begin(); ?>

   <?= $form->field($model, 'course_id')->dropDownList($courses, ['prompt' => 'Select Course']) ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
