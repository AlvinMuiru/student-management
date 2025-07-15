<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Retake Requests';
?>
<h1><?= Html::encode($this->title) ?></h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'student.first_name',
        'grade.class.class_name',
        'grade.score',
        'status',
        'created_at',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{reassess}',
            'buttons' => [
                'reassess' => function ($url, $model) {
                    return Html::a('Reassess', ['reassess', 'id' => $model->id], ['class' => 'btn btn-sm btn-warning']);
                },
            ],
        ],
    ],
]) ?>
