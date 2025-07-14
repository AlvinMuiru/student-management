<?php
use yii\helpers\Html;
?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <?php if (!Yii::$app->user->isGuest): ?>
                <?= Html::beginForm(['/site/logout'], 'post') .
                    Html::submitButton(
                        'Logout (' . Html::encode(Yii::$app->user->identity->username) . ')',
                        ['class' => 'btn btn-link logout']
                    ) .
                    Html::endForm()
                ?>
            <?php else: ?>
                <?= Html::a('Login', ['/site/login'], ['class' => 'nav-link']) ?>
                <?= Html::a('Register', ['/site/signup'], ['class' => 'nav-link']) ?>

            <?php endif; ?>
        </li>
    </ul>
</nav>
