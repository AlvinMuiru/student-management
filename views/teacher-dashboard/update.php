<?php
/** @var $teacher app\models\Teacher */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Update Profile';
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="teacher-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($teacher, 'first_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($teacher, 'last_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($teacher, 'email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($teacher, 'phone')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save Changes', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
