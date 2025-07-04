<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ClassModel $model */
/** @var array $teachers */
/** @var array $allStudents */

$this->title = 'Update Class Model: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Class Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="class-model-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'teachers' => $teachers,
        'allStudents' => $allStudents  // Make sure to pass this variable
    ]) ?>

</div>