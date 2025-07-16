<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Fee $model */

$this->title = 'Update Fee: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Fees', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fee-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
