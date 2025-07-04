<?php

use app\models\ClassModel;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Class Models';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="class-model-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Class Model', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'class_name',

            // ✅ Fix teacher name display here
            [
                'attribute' => 'teacher_id',
                'label' => 'Teacher Name',
                'value' => function ($model) {
                    return $model->teacher ? $model->teacher->fullName : '(not set)';
                },
            ],

            'schedule',
            'created_at',
            'updated_at',

            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, ClassModel $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>

</div>
