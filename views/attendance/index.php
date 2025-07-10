<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AttendanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Attendance Records';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attendance-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        [
    'attribute' => 'student_name',
    'value' => function($model) {
        return $model->student ? ($model->student->first_name . ' ' . $model->student->last_name) : 'N/A';
    },
    'filter' => Html::activeTextInput($searchModel, 'student_name', ['class' => 'form-control']),
    'label' => 'Student Name'
],

        [
            'attribute' => 'class_name',
            'value' => 'class.class_name',
            'filter' => Html::activeTextInput($searchModel, 'class_name', ['class' => 'form-control']),
            'label' => 'Class Name'
        ],
        'date',
        'status',
        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>

    
</div>