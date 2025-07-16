<?php
use yii\grid\GridView;
use yii\helpers\Html;
$this->title = 'My Fee Payments';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'label' => 'Course',
            'value' => function ($model) {
                return $model->fee->course->name ?? 'Unknown';
            }
        ],
        [
            'label' => 'Amount (KES)',
            'value' => function ($model) {
                return number_format($model->fee->amount ?? 0, 2);
            }
        ],
        'status',
        'paid_at',

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{pay} {receipt} {download}', // Added {download}
            'buttons' => [
                'pay' => function ($url, $model) {
                    if ($model->status !== 'paid') {
                        return Html::a('Pay Now', ['student-fee/pay', 'id' => $model->id], [
                            'class' => 'btn btn-sm btn-success',
                            'data' => [
                                'confirm' => 'Are you sure you want to pay this fee?',
                                'method' => 'post',
                            ]
                        ]);
                    }
                    return '';
                },
                'receipt' => function ($url, $model) {
                    if ($model->status === 'paid') {
                        return Html::a('View Receipt', ['student-fee/receipt', 'id' => $model->id], [
                            'class' => 'btn btn-sm btn-primary',
                            'target' => '_blank'
                        ]);
                    }
                    return '';
                },
                'download' => function ($url, $model) {
                    if ($model->status === 'paid') {
                        return Html::a('Download PDF', ['student-fee/download-receipt', 'id' => $model->id], [
                            'class' => 'btn btn-sm btn-outline-secondary',
                            'target' => '_blank'
                        ]);
                    }
                    return '';
                },
            ],
        ],
    ],
]); ?>
