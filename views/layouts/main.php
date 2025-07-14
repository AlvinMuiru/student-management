<?php
use hail812\adminlte3\assets\AdminLteAsset;
AdminLteAsset::register($this);
use yii\helpers\Html;
$this->beginPage();
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
<body class="hold-transition sidebar-mini layout-fixed">
<?php $this->beginBody() ?>

<div class="wrapper">

    <!-- Navbar -->
    <?= $this->render('_navbar') ?>

    <!-- Sidebar -->
    <?= $this->render('_sidebar') ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper p-3">
        <?= $content ?>
    </div>

    <!-- Footer -->
    <footer class="main-footer text-center">
        <strong>&copy; <?= date('Y') ?> My Company.</strong> All rights reserved.
    </footer>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
