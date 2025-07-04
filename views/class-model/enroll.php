<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = "Enroll Students in ".$class->class_name;
?>

<div class="class-model-enroll">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="form-group">
        <?= Html::label('Select Students', 'students') ?>
        <?= Html::dropDownList('students', 
            $currentStudents, 
            $allStudents,
            [
                'multiple' => true, 
                'class' => 'form-control',
                'size' => 15
            ]
        ) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save Enrollments', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancel', ['view', 'id' => $class->id], ['class' => 'btn btn-default']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>