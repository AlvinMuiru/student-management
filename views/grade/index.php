<?php
use yii\helpers\Html;
use app\models\RetakeRequest;
use app\components\SemesterHelper;

$this->title = 'My Grades';
?>
<h1><?= Html::encode($this->title) ?></h1>

<?php
$semester = SemesterHelper::getCurrentSemester();
?>

<?php if ($semester): ?>
    <p><strong>Current Semester:</strong> <?= Html::encode($semester->name) ?> (<?= Html::encode($semester->academicYear->year_name) ?>)</p>
<?php endif; ?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Class</th>
            <th>Score</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($grades as $grade): ?>
            <?php if (!$semester || $grade->semester_id != $semester->id) continue; ?>
            <tr>
                <td><?= $grade->class ? $grade->class->class_name : '(Class not found)' ?></td>
                <td><?= $grade->score ?></td>
                <td>
                    <?php if ($grade->passed): ?>
                        <span class="badge badge-success">Passed</span>
                    <?php else: ?>
                        <span class="badge badge-danger">Failed</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (!$grade->passed): ?>
                        <?php
                        $alreadyRequested = RetakeRequest::find()
                            ->where([
                                'grade_id' => $grade->id,
                                'student_id' => Yii::$app->user->identity->student->id
                            ])
                            ->exists();
                        ?>
                        <?php if ($alreadyRequested): ?>
                            <span class="badge badge-secondary">Requested</span>
                        <?php else: ?>
                            <?= Html::a('Register for Retake', ['grade/register-retake', 'id' => $grade->id], [
                                'class' => 'btn btn-warning btn-sm',
                                'data-method' => 'post',
                                'data-confirm' => 'Are you sure you want to request a retake for this unit?',
                            ]) ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
