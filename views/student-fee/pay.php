<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Simulate Payment';
?>

<div class="student-fee-pay box box-primary p-3">
    <h3><?= Html::encode($this->title) ?></h3>

    <p>Please confirm your payment method to simulate payment. Transaction details will be auto-generated.</p>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'payment_method')->dropDownList([
        'mpesa' => 'M-Pesa',
        'paypal' => 'PayPal',
        'equity' => 'Equity Bank',
        'coop' => 'Cooperative Bank',
        'absa' => 'ABSA',
    ], ['prompt' => 'Select Payment Method']) ?>

    <?= $form->field($model, 'bank_name')->textInput(['placeholder' => 'If applicable (leave blank for M-Pesa/PayPal)']) ?>


    <div class="form-group">
        <?= Html::submitButton('Simulate Payment', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
