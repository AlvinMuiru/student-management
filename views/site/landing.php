<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Welcome to the Student Management System';
?>
<div class="site-landing text-center">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Manage your academic journey with ease.</p>
    
    <p>
        <?= Html::a('Login', Url::to(['site/login']), ['class' => 'btn btn-primary']) ?>
    </p>
</div>
