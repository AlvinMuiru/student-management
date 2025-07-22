<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Students;
use app\models\Courses;
use app\models\Semester;

/** @var yii\web\View $this */
/** @var app\models\ClassModel $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $teachers */
/** @var array $allStudents */
?>

<div class="class-model-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- Class Information Section -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Class Information</h3>
        </div>
        <div class="panel-body">
            <?= $form->field($model, 'class_name')->textInput(['maxlength' => true]) ?>
            
<?= $form->field($model, 'course_id')->dropDownList(
    ArrayHelper::map(Courses::find()->all(), 'id', 'name'),
    ['prompt' => 'Select Course']
) ?>
      <?= $form->field($model, 'semester_id')->dropDownList(
    \yii\helpers\ArrayHelper::map(\app\models\Semester::find()->all(), 'id', function ($semester) {
        return $semester->academicYear->year_name . ' - ' . $semester->name;
    }),
    ['prompt' => 'Select Semester']
) ?>


           <?= $form->field($model, 'teacher_id')->dropDownList(
    $teachers,
    ['prompt' => 'Select Teacher']
) ?>

            <?= $form->field($model, 'schedule')->textInput(['maxlength' => true]) ?>
        </div>
    </div>



    <!-- Timestamps (hidden if not needed) -->
    <?= $form->field($model, 'created_at')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'updated_at')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>