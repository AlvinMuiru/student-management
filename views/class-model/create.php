<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Students;

/** @var yii\web\View $this */
/** @var app\models\ClassModel $model */
/** @var array $teachers */
?>

<div class="class-model-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'teachers' => $teachers,
        'allStudents' => ArrayHelper::map(
            Students::find()->orderBy('last_name, first_name')->all(), 
            'id', 
            'fullName'
        )
    ]) ?>
</div>