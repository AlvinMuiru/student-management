<?php
use hail812\adminlte3\assets\AdminLteAsset;
use yii\helpers\Html;

AdminLteAsset::register($this);
$this->beginPage();

// Define where sidebar or navbar should be hidden
$noSidebarActions = ['landing', 'about', 'contact', 'login', 'signup'];
$noNavbarActions = ['about', 'contact']; // Keep navbar for landing, login, signup

$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;

$hideSidebar = $controller === 'site' && in_array($action, $noSidebarActions);
$hideNavbar  = $controller === 'site' && in_array($action, $noNavbarActions);

// Optional: hide footer only on login/signup, not on landing
$hideFooter = $controller === 'site' && in_array($action, ['login', 'signup']);
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

    <!-- Content Wrapper -->
    <div class="content-wrapper p-3">
        <?= $content ?>
    </div>

    <!-- Footer -->
    <?php if (!$hideFooter): ?>
        <footer class="main-footer text-center">
            <strong>&copy; <?= date('Y') ?> TechBridge College.</strong> All rights reserved.
        </footer>
    <?php endif; ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
