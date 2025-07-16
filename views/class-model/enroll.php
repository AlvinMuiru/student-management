<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var \app\models\ClassModel $class */
/** @var array $allStudents - [id => "Full Name"] */
/** @var array $currentStudents - list of student IDs already enrolled */

$this->title = "Enroll Students in " . $class->class_name;
?>

<div class="class-model-enroll">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="form-group">
        <?= Html::label('Select Students', 'students') ?>
         <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9;">
    <?= Html::checkboxList('students', $currentStudents, $allStudents, [
        'separator' => '<br>',
        'itemOptions' => ['class' => 'form-check-input']
    ]) ?>
</div>


        
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save Enrollments', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancel', ['view', 'id' => $class->id], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
