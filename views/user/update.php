<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = 'Update User: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="user-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'username')->textInput() ?>
   
    <?= $form->field($model, 'role')->dropDownList([
        'student' => 'Student',
        'teacher' => 'Teacher',
        'admin' => 'Admin',
    ]) ?>

    <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    
    <?php ActiveForm::end(); ?>
</div>
