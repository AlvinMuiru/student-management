<?php

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Activity Logs';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        [
            'attribute' => 'user_id',
            'label' => 'Username',
            'value' => function ($log) {
                return $log->user ? $log->user->username : 'Unknown';
            },
        ],
        'action',
        'controller',
        'model',
        'model_id',
        'description:ntext',
        'ip_address',
        'created_at',
    ],
]); ?>
