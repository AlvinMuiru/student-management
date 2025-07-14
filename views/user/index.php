<?php

use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Manage Users';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'username',
            'email',
            'role', // if you have a role column
            'created_at',
            'updated_at',

            
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}', // Show update and delete
            'urlCreator' => function ($action, $model, $key, $index) {
                return \yii\helpers\Url::to([$action, 'id' => $model->id]);
            },
        ],
        ],
    ]); ?>
</div>
