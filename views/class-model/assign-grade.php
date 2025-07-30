<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Students;

/** @var yii\web\View $this */
/** @var app\models\forms\AssignGradeForm $model */
/** @var app\models\ClassModel $class */

$this->title = 'Assign Grade: ' . $class->class_name;
$this->params['breadcrumbs'][] = $this->title;

// ✅ Fetch eligible students BEFORE rendering the dropdown
$students = Students::find()
    ->alias('s')
    ->innerJoin('class_assignments ca', 'ca.student_id = s.id')
    ->innerJoin('student_fees sf', 'sf.student_id = s.id')
    ->where([
        'ca.class_id' => $class->id,
        'sf.status' => 'paid'
    ])
    ->groupBy('s.id')
    ->all();

$studentList = ArrayHelper::map($students, 'id', function ($student) {
    return $student->first_name . ' ' . $student->last_name . ' (' . $student->reg_no . ')';
});
?>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
    </div>

    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'student_id')->dropDownList($studentList, ['prompt' => 'Select a student']) ?>

        <?= $form->field($model, 'cat_score')->textInput(['type' => 'number', 'step' => 'any', 'id' => 'cat']) ?>
        <?= $form->field($model, 'exam_score')->textInput(['type' => 'number', 'step' => 'any', 'id' => 'exam']) ?>
        <?= $form->field($model, 'score')->textInput(['readonly' => true, 'id' => 'final']) ?>

        <?= Html::activeHiddenInput($model, 'class_id', ['value' => $class->id]) ?>

        <div class="form-group">
            <?= Html::submitButton('Save Grade', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$js = <<<JS
function calculateFinalScore() {
    let cat = parseFloat(document.getElementById("cat").value) || 0;
    let exam = parseFloat(document.getElementById("exam").value) || 0;
    let final = cat + exam;
    document.getElementById("final").value = final.toFixed(2);
}
document.getElementById("cat").addEventListener("input", calculateFinalScore);
document.getElementById("exam").addEventListener("input", calculateFinalScore);
JS;
$this->registerJs($js);
?>
<?php
$fetchUrl = \yii\helpers\Url::to(['grade/get-scores']);
$js = <<<JS
function calculateFinalScore() {
    let cat = parseFloat(document.getElementById("cat").value) || 0;
    let exam = parseFloat(document.getElementById("exam").value) || 0;
    let final = cat + exam;
    document.getElementById("final").value = final.toFixed(2);
}

document.getElementById("cat").addEventListener("input", calculateFinalScore);
document.getElementById("exam").addEventListener("input", calculateFinalScore);

// AJAX: Fetch scores when student is selected
$('#assigngradeform-student_id').on('change', function() {
    let studentId = $(this).val();
    let classId = {$class->id};

    if (studentId) {
        $.get('$fetchUrl', {studentId: studentId, classId: classId}, function(data) {
            if (data.success) {
                $('#cat').val(data.cat_score);
                $('#exam').val(data.exam_score);
                calculateFinalScore();
            } else {
                $('#cat').val('');
                $('#exam').val('');
                $('#final').val('');
            }
        });
    } else {
        $('#cat').val('');
        $('#exam').val('');
        $('#final').val('');
    }
});
JS;

$this->registerJs($js);
?>
