<?php
/** @var $teacher app\models\Teacher */
/** @var $classes app\models\ClassModel[] */
use yii\helpers\Html;

$this->title = 'Teacher Dashboard';
?>

<div class="teacher-dashboard container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-light shadow-sm">
                <div class="card-body">
                    <h3 class="card-title">
                        👋 Welcome, <?= Html::encode($teacher->first_name) ?>!
                    </h3>
                    <p class="card-text">Manage your profile and view assigned classes.</p>
                    <?= Html::a('✏️ Edit Profile', ['update'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📚 Your Assigned Classes</h5>
                </div>
                <div class="card-body">
                    <?php if (count($classes)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Class Name</th>
                                        <th>Semester</th>
                                        <th>Academic Year</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($classes as $index => $class): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><strong><?= Html::encode($class->class_name) ?></strong></td>
                                            <td><?= Html::encode($class->semester->name ?? '-') ?></td>
                                            <td><?= Html::encode($class->semester->academicYear->year_name ?? '-') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No classes assigned yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
