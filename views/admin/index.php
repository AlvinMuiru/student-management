<?php

/** @var yii\web\View $this */

use yii\helpers\Url;

$this->title = 'Admin Dashboard';

// Get counts
$totalStudents = \app\models\Students::find()->count();
$totalTeachers = \app\models\Teacher::find()->count();
$totalClasses = \app\models\ClassModel::find()->count();
$todaysAttendance = \app\models\Attendance::find()
    ->where(['date' => date('Y-m-d')])
    ->count();

?>

<div class="admin-dashboard container mt-4">
    <h1 class="mb-4"><?= $this->title ?></h1>

    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Students</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $totalStudents ?></h5>
                    <p class="card-text">Total registered students</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Teachers</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $totalTeachers ?></h5>
                    <p class="card-text">Total registered teachers</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Classes</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $totalClasses ?></h5>
                    <p class="card-text">Classes created in the system</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Today's Attendance</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $todaysAttendance ?></h5>
                    <p class="card-text">Attendance records for <?= date('d M Y') ?></p>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="mt-4">
        <h4>Quick Links</h4>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <a href="<?= Url::to(['class-model/index']) ?>">📚 Manage Classes</a>
            </li>
            <li class="list-group-item">
                <a href="<?= Url::to(['user/index']) ?>">👥 Manage Users</a>
            </li>
        </ul>
    </div>
</div>
