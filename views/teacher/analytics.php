<?php
use yii\helpers\Html;

$this->title = 'Grading Analytics';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php if (empty($analytics)): ?>
    <p>No analytics data available. Ensure students have been graded.</p>
<?php else: ?>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Class</th>
                <th>Average Score</th>
                <th>Highest Score</th>
                <th>Lowest Score</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($analytics as $data): ?>
                <tr>
                    <td><?= Html::encode($data['className']) ?></td>
                    <td><?= $data['averageScore'] ?></td>
                    <td><?= $data['highestScore'] ?></td>
                    <td><?= $data['lowestScore'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
