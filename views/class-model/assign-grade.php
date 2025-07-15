<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\forms\AssignGradeForm $model */
/** @var app\models\ClassModel $class */
/** @var app\models\Students[] $students */

$this->title = 'Assign Grade';
$this->params['breadcrumbs'][] = ['label' => 'Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $class->class_name, 'url' => ['view', 'id' => $class->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="class-grade-form">
    <h1><?= Html::encode($this->title) ?> - <?= Html::encode($class->class_name) ?></h1>

    <?php $form = ActiveForm::begin(
        [
    'id' => 'assign-grade-form',
    'action' => ['class-model/assign-grade', 'classId' => $class->id],
    'method' => 'post',
]
    ); ?>

    <?= $form->field($model, 'student_id')->dropDownList(
        \yii\helpers\ArrayHelper::map($students, 'id', fn($s) => $s->first_name . ' ' . $s->last_name),
        ['prompt' => 'Select Student']
    ) ?>

    <?= $form->field($model, 'score')->input('number', ['min' => 0, 'max' => 100]) ?>

    <div class="form-group">
        <?= Html::submitButton('Assign Grade', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
