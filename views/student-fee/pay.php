<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Simulate Payment';
?>

<div class="student-fee-pay box box-primary p-3">
    <h3><?= Html::encode($this->title) ?></h3>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'payment_method')->dropDownList([
        'mpesa' => 'M-Pesa',
        'paypal' => 'PayPal',
        'equity' => 'Equity Bank',
        'coop' => 'Cooperative Bank',
        'absa' => 'ABSA',
    ], ['prompt' => 'Select Payment Method']) ?>

    <?= $form->field($model, 'bank_name')->textInput(['placeholder' => 'If applicable']) ?>

    <?= $form->field($model, 'transaction_ref')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([
        'pending' => 'Pending',
        'paid' => 'Paid',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Pay Now', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
