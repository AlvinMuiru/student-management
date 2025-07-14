<?php

/** @var yii\web\View $this */
$this->title = 'Admin Dashboard';

?>
<div class="admin-dashboard">
    <h1><?= $this->title ?></h1>
    <p>Welcome, Admin!</p>

    <ul>
        <li><a href="<?= \yii\helpers\Url::to(['class-model/index']) ?>">Manage Classes</a></li>
        <li><a href="<?= \yii\helpers\Url::to(['user/index']) ?>">Manage Users</a></li>
    </ul>
</div>
