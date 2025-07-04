<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ClassModel;
use yii\helpers\ArrayHelper;

$this->title = 'Assign Class to ' . $student->fullName;
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $student->fullName, 'url' => ['view', 'id' => $student->id]];
$this->params['breadcrumbs'][] = 'Assign Class';

$classes = ArrayHelper::map(ClassModel::find()->all(), 'id', 'class_name');
?>

<div class="student-assign-class">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="student-form">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'class_id')->dropDownList(
            $classes,
            ['prompt' => 'Select Class']
        ) ?>

        <div class="form-group">
            <?= Html::submitButton('Assign', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>