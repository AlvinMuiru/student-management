<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ClassModel $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Class Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="class-model-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Record Attendance', ['attendance/create', 'class_id' => $model->id], ['class' => 'btn btn-primary']) ?>

    <?= Html::a('Enroll Students', ['enroll', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'class_name',
           [
            'attribute' => 'teacher_id',
            'value' => $model->teacher->fullName ?? 'Not Assigned',
            'label' => 'Teacher Name',
        ] ,
            'schedule',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
