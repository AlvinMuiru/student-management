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
                <li class="nav-item">
                <?= Html::a('Login', ['/site/login'], ['class' => 'nav-link']) ?>
            </li>
            <li class="nav-item">
                <?= Html::a('Register', ['/site/signup'], ['class' => 'nav-link']) ?>
            </li>

            <?php endif; ?>
        </li>
    </ul>
</nav>
