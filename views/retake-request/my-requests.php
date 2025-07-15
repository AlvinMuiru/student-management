<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'My Retake Requests';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="retake-requests-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'grade.class.class_name',
                'label' => 'Class Name',
                'value' => function ($model) {
                    return $model->grade->class->class_name ?? 'N/A';
                }
            ],
            [
                'attribute' => 'grade.score',
                'label' => 'Original Score',
            ],
            'status',
            'created_at',
        ],
    ]) ?>

</div>
