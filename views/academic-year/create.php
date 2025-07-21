<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AcademicYear $model */

$this->title = 'Create Academic Year';
$this->params['breadcrumbs'][] = ['label' => 'Academic Years', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="academic-year-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
