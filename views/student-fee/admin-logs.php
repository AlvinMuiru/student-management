<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\FeesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Student Payment Logs';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="student-fee-search">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['admin-logs'],
    ]); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($searchModel, 'student_name')->textInput(['placeholder' => 'Search by Student']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($searchModel, 'course_name')->textInput(['placeholder' => 'Search by Course']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($searchModel, 'receipt_number')->textInput(['placeholder' => 'Search by Receipt #']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($searchModel, 'status')->dropDownList([
                '' => 'All',
                'paid' => 'Paid',
                'pending' => 'Pending',
                'rejected' => 'Rejected',
            ], ['prompt' => 'Select Status']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($searchModel, 'paid_at')->input('date') ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset', ['admin-logs'], ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'label' => 'Student',
            'value' => function ($model) {
                return $model->student ? $model->student->fullName : 'N/A';
            }
        ],
        [
            'label' => 'Course',
            'value' => function ($model) {
                return $model->fee->course->name ?? 'N/A';
            }
        ],
        [
            'label' => 'Amount Paid (KES)',
            'value' => function ($model) {
                return number_format($model->amount_paid, 2);
            }
        ],
        'status',
        'receipt_number',
        'paid_at',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{receipt} {download}',
            'buttons' => [
                'receipt' => function ($url, $model) {
                    return Html::a('View Receipt', ['receipt', 'id' => $model->id], [
                        'class' => 'btn btn-sm btn-primary',
                        'target' => '_blank'
                    ]);
                },
                'download' => function ($url, $model) {
                    return Html::a('Download PDF', ['download-receipt', 'id' => $model->id], [
                        'class' => 'btn btn-sm btn-secondary',
                        'target' => '_blank'
                    ]);
                },
            ],
        ],
    ],
]); ?>
