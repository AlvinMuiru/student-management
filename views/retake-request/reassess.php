<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Reassess Grade';
?>

<h1><?= Html::encode($this->title) ?></h1>

<p>Student: <?= Html::encode($retake->student->first_name) ?> <br>
Class: <?= Html::encode($retake->grade->class->class_name) ?> <br>
Previous Score: <?= Html::encode($grade->score) ?></p>

<?php $form = ActiveForm::begin(); ?>

<?= Html::label('New Score') ?>
<?= Html::input('number', 'score', $grade->score, ['min' => 0, 'max' => 100]) ?>

<br><br>
<?= Html::submitButton('Update Score', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
