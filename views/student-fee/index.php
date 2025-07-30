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
                return number_format($model->fee->amount/2 ?? 0, 2);
            }
        ],
        'status',
        'paid_at',
        [
            'attribute' => 'receipt_number',
            'label' => 'Receipt No.',
        ],

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{simulate} {receipt} {download}', // renamed {pay} → {simulate}
            'buttons' => [

                // 🟢 Payment Simulation (for unpaid fees)
                'simulate' => function ($url, $model) {
                    if ($model->status !== 'paid') {
                        return Html::a('Pay Now', ['student-fee/simulate', 'id' => $model->id], [
                            'class' => 'btn btn-sm btn-success',
                            'data' => [
                                'confirm' => 'Are you sure you want to simulate this payment?',
                                'method' => 'post',
                            ]
                        ]);
                    }
                    return '';
                },

                // 🧾 View Receipt (if paid)
                'receipt' => function ($url, $model) {
                    if ($model->status === 'paid') {
                        return Html::a('View Receipt', ['student-fee/receipt', 'id' => $model->id], [
                            'class' => 'btn btn-sm btn-primary',
                            'target' => '_blank'
                        ]);
                    }
                    return '';
                },

                // 🧾 Download PDF Receipt
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
