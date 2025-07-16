<?php
use yii\helpers\Html;

$this->title = "Receipt";
?>

<div style="border:1px solid #ccc; padding:20px; max-width:600px; margin:0 auto; font-family:sans-serif">
    <h2 style="text-align:center;">Payment Receipt</h2>

    <p><strong>Student:</strong> <?= $model->student->first_name . ' ' . $model->student->last_name ?></p>
    <p><strong>Reg No:</strong> <?= $model->student->reg_no ?></p>

    <p><strong>Course:</strong>
        <?= $model->fee && $model->fee->course
            ? $model->fee->course->name
            : '<span style="color:red;">[Course Deleted]</span>' ?>
    </p>

    <p><strong>Description:</strong>
        <?= $model->fee
            ? $model->fee->description
            : '<span style="color:red;">[Fee Deleted]</span>' ?>
    </p>

    <p><strong>Amount:</strong>
        <?= $model->fee
            ? 'KES ' . number_format($model->fee->amount, 2)
            : '<span style="color:red;">[Amount Unknown]</span>' ?>
    </p>

    <p><strong>Status:</strong> <?= ucfirst($model->status) ?></p>
    <p><strong>Paid At:</strong> <?= $model->paid_at ?></p>

    <hr>
    <p style="text-align:center;">Thank you for your payment.</p>
</div>
