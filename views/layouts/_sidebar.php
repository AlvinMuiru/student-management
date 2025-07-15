<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= Yii::$app->homeUrl ?>" class="brand-link">
        <span class="brand-text font-weight-light"><?= Html::encode(Yii::$app->name) ?></span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column">

                <?php if (!Yii::$app->user->isGuest): ?>
                    <?php if (Yii::$app->user->identity->role === 'student'): ?>
                        <li class="nav-item">
                            <?= Html::a('Student Dashboard', ['/dashboard/index'], ['class' => 'nav-link']) ?>
                        </li>
                         <li class="nav-item">
                             <?= Html::a('<i class="nav-icon fas fa-clipboard"></i> <p>My Grades</p>', ['/grade/index'], ['class' => 'nav-link']) ?>
                          </li>
                          <li class="nav-item">
                            <?= Html::a('My Retake Requests', ['/retake-request/my-requests'], ['class' => 'nav-link']) ?>

                          </li>
                         


                    <?php elseif (Yii::$app->user->identity->role === 'teacher'): ?>
                        <li class="nav-item">
                            <?= Html::a('Teacher Dashboard', ['/teacher-dashboard/index'], ['class' => 'nav-link']) ?>
                        </li>
                        <li class="nav-item">
                            <?= Html::a('Manage Classes', ['/class-model/index'], ['class' => 'nav-link']) ?>
                        </li>
                        <li class="nav-item">
                            <?= Html::a('Mark Attendance', ['/attendance/index'], ['class' => 'nav-link']) ?>
                        </li>
                        <li class="nav-item">
                           <?= Html::a('Retake Requests', ['/retake-request/index'], ['class' => 'nav-link']) ?>

                        </li>

                    <?php elseif (Yii::$app->user->identity->role === 'admin'): ?>
                        <li class="nav-item">
                            <?= Html::a('Admin Dashboard', ['/admin/index'], ['class' => 'nav-link']) ?>
                        </li>
                        <li class="nav-item">
                            <?= Html::a('All Students', ['/student/index'], ['class' => 'nav-link']) ?>
                        </li>
                        <li class="nav-item">
                            <?= Html::a('All Teachers', ['/teacher/index'], ['class' => 'nav-link']) ?>
                        </li>
                        <li class="nav-item">
                            <?= Html::a('Classes', ['/class-model/index'], ['class' => 'nav-link']) ?>
                        </li>
                        <li class="nav-item">
                            <?= Html::a('Attendance', ['/attendance/index'], ['class' => 'nav-link']) ?>
                        </li>
                        <li class="nav-item">
                            <?= Html::a('Retake Requests', ['/retake-request/index'], ['class' => 'nav-link']) ?>

                        </li>
                    <?php endif; ?>
                <?php endif; ?>

            </ul>
        </nav>
    </div>
</aside>
