<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'My Retake Requests';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="retake-request-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p class="text-muted">Below are your failed units. You can register for retake where applicable.</p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label' => 'Class Name',
                'value' => function ($model) {
                    return $model->class->class_name ?? '(not found)';
                },
            ],
            'score',

            [
                'label' => 'Request Status',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->request_id) {
                        return Html::tag('span', $model->request_status, ['class' => 'badge badge-info']);
                    }
                    return Html::tag('span', 'Not Requested', ['class' => 'badge badge-warning']);
                },
            ],

            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->request_id) {
                        return Html::tag('span', 'Already Requested', ['class' => 'text-muted']);
                    }
                    return Html::a('Register for Retake', ['grade/register-retake', 'id' => $model->id], [
                        'class' => 'btn btn-sm btn-primary',
                        'data-confirm' => 'Are you sure you want to request a retake for this class?',
                        'data-method' => 'post',
                    ]);
                }
            ],
        ],
    ]); ?>
</div>
