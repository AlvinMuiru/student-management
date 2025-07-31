<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'TechBridge College of Computing';
?>

<div class="site-landing text-center" style="padding: 50px; background: #f8f9fa;">
    <h1 class="display-4"><?= Html::encode($this->title) ?></h1>
    <p class="lead">Empowering students with hands-on skills in Computer Science, Software Engineering, and Information Technology.</p>

    <div class="row justify-content-center mt-4 mb-4">
        <div class="col-md-8">
            <p>
                Welcome to the official Student Portal for TechBridge College — your gateway to academic success and administrative convenience.
                Our college is dedicated to producing industry-ready graduates by offering up-to-date computing programs,
                modern labs, qualified instructors, and a learning environment that fosters innovation and technical excellence.
            </p>
            <p>
                Through this system, students can manage course enrollments, monitor grades, track fee payments,
                and stay updated with academic calendars and exam schedules.
            </p>
        </div>
    </div>

    <p>
        <?= Html::a('Login to Student Portal', ['site/login'], ['class' => 'btn btn-primary btn-lg']) ?>
    </p>
</div>
