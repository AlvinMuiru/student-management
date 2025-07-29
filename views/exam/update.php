<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Exam $model */
/** @var array $classes */
/** @var array $semesters */
/** @var array $invigilators */

$this->title = 'Update Exam Schedule';
?>

<div class="exam-update container">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'classes' => $classes,
        'semesters' => $semesters,
        'invigilators' => $invigilators,
    ]) ?>
</div>
