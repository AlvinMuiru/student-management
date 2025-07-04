<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Take Attendance for ' . $class->class_name;
$this->params['breadcrumbs'][] = ['label' => 'Classes', 'url' => ['class-model/index']];
$this->params['breadcrumbs'][] = ['label' => $class->class_name, 'url' => ['class-model/view', 'id' => $class->id]];
$this->params['breadcrumbs'][] = 'Take Attendance';
?>

<div class="attendance-take">
    <h1><?= Html::encode($this->title) ?></h1>
    <h3><?= date('F j, Y') ?></h3>

    <?php $form = ActiveForm::begin(); ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($models as $i => $model): ?>
            <tr>
                <td><?= $model->student->fullName ?></td>
                <td>
                    <?= $form->field($model, "[$i]status")->dropDownList([
                        'present' => 'Present',
                        'absent' => 'Absent',
                        'late' => 'Late'
                    ], ['label' => false])->label(false) ?>
                    <?= Html::activeHiddenInput($model, "[$i]student_id") ?>
                    <?= Html::activeHiddenInput($model, "[$i]class_id") ?>
                    <?= Html::activeHiddenInput($model, "[$i]date") ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="form-group">
        <?= Html::submitButton('Save Attendance', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>