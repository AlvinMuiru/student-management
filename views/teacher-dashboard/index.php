<?php
/** @var $teacher app\models\Teacher */
/** @var $classes app\models\ClassModel[] */
use yii\helpers\Html;

$this->title = 'Teacher Dashboard';
?>
<h1>Welcome, <?= Html::encode($teacher->first_name) ?>!</h1>

<p><?= Html::a('Edit Profile', ['update'], ['class' => 'btn btn-primary']) ?></p>

<h2>Your Classes</h2>
<?php if (count($classes)): ?>
    <ul>
        <?php foreach ($classes as $class): ?>
           <li><strong><?= Html::encode($class->class_name) ?></strong></li>

        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No classes assigned yet.</p>
<?php endif; ?>
