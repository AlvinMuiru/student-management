<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Semester $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="semester-form">
<?php $form = ActiveForm::begin(); ?>
<?php if ($model->hasErrors()) {
    echo '<div class="alert alert-danger">';
    echo $form->errorSummary($model); // corrected line
    echo '</div>';
} ?>


    <?= $form->field($model, 'academic_year_id')->textInput() ?>

    <?= $form->field($model, 'name')->dropDownList([ 'Semester 1' => 'Semester 1', 'Semester 2' => 'Semester 2', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'start_date')->textInput() ?>

    <?= $form->field($model, 'end_date')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList([ 'active' => 'Active', 'ended' => 'Ended', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
