<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Students;

/** @var yii\web\View $this */
/** @var app\models\ClassModel $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $teachers */
/** @var array $allStudents */
?>

<div class="class-model-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- Class Information Section -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Class Information</h3>
        </div>
        <div class="panel-body">
            <?= $form->field($model, 'class_name')->textInput(['maxlength' => true]) ?>
     
        

           <?= $form->field($model, 'teacher_id')->dropDownList(
    $teachers,
    ['prompt' => 'Select Teacher']
) ?>

            <?= $form->field($model, 'schedule')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <!-- Student Enrollment Section -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Enroll Students</h3>
        </div>
        <div class="panel-body">
            <?= $form->field($model, 'enrolledStudents')->checkboxList(
    $allStudents,
    [
        'item' => function($index, $label, $name, $checked, $value) {
            return '<div class="checkbox">' .
                Html::checkbox($name, $checked, [
                    'value' => $value,
                    'label' => $label,
                ]) .
                               '</div>';
                    }
                ]
            )->label(false) ?>
        </div>
    </div>

    <!-- Timestamps (hidden if not needed) -->
    <?= $form->field($model, 'created_at')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'updated_at')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>