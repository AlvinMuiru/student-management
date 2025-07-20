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

$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['depends' => [\yii\web\JqueryAsset::class]]);
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

    <hr>

    <!-- Chart Section -->
    <div class="mt-5">
        <h4>📊 Paid Students Per Course</h4>
        <div class="card">
            <div class="card-body">
                <canvas id="paidChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<?php
$chartDataUrl = Url::to(['admin/chart-data']);
$js = <<<JS
fetch('$chartDataUrl')
    .then(response => response.json())
    .then(data => {
        const labels = data.map(d => d.course);
        const values = data.map(d => d.paid);

        const ctx = document.getElementById('paidChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Paid Students',
                    data: values,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    });
JS;

$this->registerJs($js);
?>
<?php
$this->title = 'Unpaid Students per Course';
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<div class="container mt-4">
    <h1><?= $this->title ?></h1>

    <canvas id="paidChart" height="100"></canvas>
    <hr>
    <canvas id="unpaidChart" height="100"></canvas>
</div>

<?php
$chartDataUrl = \yii\helpers\Url::to(['admin/chart-data']);
$js = <<<JS
fetch('$chartDataUrl')
    .then(response => response.json())
    .then(data => {
        // Paid Chart
        const paidLabels = data.paid.map(d => d.course);
        const paidCounts = data.paid.map(d => d.count);

        new Chart(document.getElementById('paidChart'), {
            type: 'bar',
            data: {
                labels: paidLabels,
                datasets: [{
                    label: 'Paid Students',
                    data: paidCounts,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: true } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Unpaid Chart
        const unpaidLabels = data.unpaid.map(d => d.course);
        const unpaidCounts = data.unpaid.map(d => d.count);

        new Chart(document.getElementById('unpaidChart'), {
            type: 'bar',
            data: {
                labels: unpaidLabels,
                datasets: [{
                    label: 'Unpaid Students',
                    data: unpaidCounts,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: true } },
                scales: { y: { beginAtZero: true } }
            }
        });
    });
JS;

$this->registerJs($js);
?>
