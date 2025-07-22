<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/** @var $this yii\web\View */
/** @var $student app\models\Students */

$this->title = 'Student Dashboard - ' . $student->fullName;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="dashboard-index">
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <!-- Personal Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <?= DetailView::widget([
                                'model' => $student,
                                'attributes' => [
                                    'first_name',
                                    'last_name',
                                    'reg_no',
                                    'birthdate:date',
                                    'email:email',
                                    'phone',
                                    'address',
                                ],
                            ]) ?>
                            <?= Html::a('Update Information', ['student/update', 'id' => $student->id], ['class' => 'btn btn-primary mt-2']) ?>
                        </div>
                    </div>
                </div>

                <!-- Attendance Summary -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Attendance Summary</h5>
                        </div>
                        <div class="card-body">
                            <?= GridView::widget([
                                'dataProvider' => new \yii\data\ArrayDataProvider([
                                    'allModels' => $student->getAttendanceSummary(),
                                    'pagination' => false,
                                ]),
                                'columns' => [
                                    ['attribute' => 'class_name', 'label' => 'Class'],
                                    ['attribute' => 'present_count', 'label' => 'Present'],
                                    ['attribute' => 'absent_count', 'label' => 'Absent'],
                                    [
                                        'attribute' => 'attendance_percentage',
                                        'label' => 'Percentage',
                                        'value' => fn($model) =>
                                            Yii::$app->formatter->asPercent($model['attendance_percentage'] / 100),
                                    ],
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php
              $semester = \app\components\SemesterHelper::getCurrentSemester();
              $semesterId = $semester ? $semester->id : 0;
            ?>

            <!-- Class Schedule -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Class Schedule - <?= $semester ? Html::encode($semester->name) : 'No Active Semester' ?></h5>
                        </div>
                        <div class="card-body">
                            <?= GridView::widget([
                                'dataProvider' => new \yii\data\ActiveDataProvider([
                                    'query' => $student->getClasses()
                                        ->andWhere(['semester_id' => $semesterId]),
                                    'pagination' => false,
                                ]),
                                'columns' => [
                                    'class_name',
                                    [
                                        'attribute' => 'teacher_name',
                                        'value' => fn($model) =>
                                            $model->teacher ? $model->teacher->fullName : 'N/A',
                                    ],
                                    'schedule',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
