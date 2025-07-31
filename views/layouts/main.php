<?php
use hail812\adminlte3\assets\AdminLteAsset;
use yii\helpers\Html;

AdminLteAsset::register($this);
$this->beginPage();

// Actions where sidebar should be hidden
$noSidebarActions = ['landing', 'about', 'contact', 'login', 'signup'];

// Actions where navbar should also be hidden (a smaller subset)
$noNavbarActions = ['landing', 'about', 'contact'];

$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;

$hideSidebar = $controller === 'site' && in_array($action, $noSidebarActions);
$hideNavbar  = $controller === 'site' && in_array($action, $noNavbarActions);
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <?= Html::csrfMetaTags() ?> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<?php
$bodyClass = $hideSidebar ? 'hold-transition layout-top-nav' : 'hold-transition sidebar-mini layout-fixed';
?>
<body class="<?= $bodyClass ?>">

<?php $this->beginBody() ?>

<div class="wrapper">

    <!-- Navbar -->
    <?php if (!$hideNavbar): ?>
        <?= $this->render('_navbar') ?>
    <?php endif; ?>

    <!-- Sidebar -->
    <?php if (!$hideSidebar): ?>
        <?= $this->render('_sidebar') ?>
    <?php endif; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper p-3">
        <?= $content ?>
    </div>

    <!-- Footer -->
    <?php if (!$hideSidebar): ?>
        <footer class="main-footer text-center">
            <strong>&copy; <?= date('Y') ?> My Company.</strong> All rights reserved.
        </footer>
    <?php endif; ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
