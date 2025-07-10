<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $student app\models\Students */

$this->title = 'Student Dashboard - ' . $student->fullName;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dashboard-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Personal Information</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $student,
                        'attributes' => [
                            'first_name',
                            'last_name',
                            'birthdate:date',
                            'email:email',
                            'phone',
                            'address',
                        ],
                    ]) ?>
                    
                    <?= Html::a('Update Information', ['student/update', 'id' => $student->id], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Attendance Summary</h3>
                </div>
                <div class="panel-body">
                    <?= GridView::widget([
                        'dataProvider' => new \yii\data\ArrayDataProvider([
                            'allModels' => $student->getAttendanceSummary(),
                            'pagination' => false,
                        ]),
                        'columns' => [
                            [
                                'attribute' => 'class_name',
                                'label' => 'Class',
                            ],
                            [
                                'attribute' => 'present_count',
                                'label' => 'Present',
                            ],
                            [
                                'attribute' => 'absent_count',
                                'label' => 'Absent',
                            ],
                            [
                                'attribute' => 'attendance_percentage',
                                'label' => 'Percentage',
                                'value' => function($model) {
                                    return Yii::$app->formatter->asPercent($model['attendance_percentage'] / 100);
                                },
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Class Schedule</h3>
                </div>
                <div class="panel-body">
                    <?= GridView::widget([
                        'dataProvider' => new \yii\data\ActiveDataProvider([
                            'query' => $student->getClasses(),
                            'pagination' => false,
                        ]),
                        'columns' => [
                            'class_name',
                            [
                                'attribute' => 'teacher_name',
                                'value' => function($model) {
                                    return $model->teacher ? $model->teacher->fullName : 'N/A';
                                },
                            ],
                            'schedule',
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>