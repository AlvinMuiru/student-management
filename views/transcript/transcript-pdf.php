<?php use yii\helpers\Html; ?>
<pre><?= print_r($transcriptData, true) ?></pre>

<h2 style="text-align:center;">Academic Transcript</h2>
<p><strong>Name:</strong> <?= Html::encode($student->first_name . ' ' . $student->last_name) ?><br>
   <strong>Reg No:</strong> <?= Html::encode($student->reg_no) ?></p>
<hr>

<?php if (empty($transcriptData)): ?>
    <p>No transcript data available.</p>
<?php else: ?>
    <?php foreach ($transcriptData as $year => $semesters): ?>
        <h3>📘 Academic Year: <?= Html::encode($year) ?></h3>
        <?php foreach ($semesters as $semester => $courses): ?>
            <h4>📚 Semester: <?= Html::encode($semester) ?></h4>
            <table width="100%" border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th>Class Name</th>
                        <th>Score</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?= Html::encode($course['class_name']) ?></td>
                            <td><?= Html::encode($course['score']) ?></td>
                            <td><?= Html::encode($course['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php endif; ?>
