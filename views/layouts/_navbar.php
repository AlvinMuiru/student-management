<?php
use yii\helpers\Html;
use yii\helpers\Url;

$currentAction = Yii::$app->controller->action->id;
$showLandingLink = in_array($currentAction, ['login', 'signup']);
?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav ml-auto">

        <?php if ($showLandingLink): ?>
            <li class="nav-item">
                <?= Html::a('<i class="fas fa-home mr-1"></i> Back to Home', ['site/landing'], ['class' => 'nav-link']) ?>
            </li>
        <?php endif; ?>

        <?php if (Yii::$app->user->isGuest): ?>
            <li class="nav-item">
                <?= Html::a('Login', ['/site/login'], ['class' => 'nav-link']) ?>
            </li>
            <li class="nav-item">
                <?= Html::a('Register', ['/site/signup'], ['class' => 'nav-link']) ?>
            </li>
        <?php else: ?>
            <li class="nav-item">
                <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline']) .
                    Html::submitButton(
                        'Logout (' . Html::encode(Yii::$app->user->identity->username) . ')',
                        ['class' => 'btn btn-link nav-link logout', 'style' => 'padding: 0;']
                    ) .
                    Html::endForm()
                ?>
            </li>
        <?php endif; ?>

    </ul>
</nav>
