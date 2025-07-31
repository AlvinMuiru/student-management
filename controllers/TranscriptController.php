<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use Mpdf\Mpdf;
use app\models\Students;
use app\models\Grade;

class TranscriptController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'download'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // only authenticated users
                    ],
                ],
            ],
        ];
    }

    /**
     * Renders the transcript on screen
     */
    public function actionIndex()
    {
        $userId = Yii::$app->user->id;
        $student = Student::findOne(['user_id' => $userId]);

        if (!$student) {
            throw new \yii\web\NotFoundHttpException("Student profile not found.");
        }

        $grades = Grade::find()
            ->where(['student_id' => $student->id])
            ->with(['class.semester.academicYear'])
            ->all();

        $transcriptData = [];

        foreach ($grades as $grade) {
            $class = $grade->class;
            $semester = $class->semester ?? null;
            $academicYear = $semester->academicYear ?? null;

            $yearName = $academicYear->year_name ?? 'Unknown Year';
            $semesterName = $semester->name ?? 'Unknown Semester';
            $className = $class->class_name ?? 'N/A';

            $transcriptData[$yearName][$semesterName][] = [
                'class_name' => $className,
                'score' => number_format($grade->score, 2),
                'status' => $grade->score >= 50 ? 'Passed' : 'Failed',
            ];
        }

        return $this->render('index', [
            'student' => $student,
            'transcriptData' => $transcriptData,
        ]);
    }

    /**
     * Downloads the transcript as PDF
     */
    public function actionDownload()
    {
        $userId = Yii::$app->user->id;
        $student = Students::findOne(['user_id' => $userId]);

        if (!$student) {
            throw new \yii\web\NotFoundHttpException("Student profile not found.");
        }

        $grades = Grade::find()
            ->where(['student_id' => $student->id])
            ->with(['class.semester.academicYear'])
            ->all();

        $transcriptData = [];

        foreach ($grades as $grade) {
            $class = $grade->class;
            $semester = $class->semester ?? null;
            $academicYear = $semester->academicYear ?? null;

            $yearName = $academicYear->year_name ?? 'Unknown Year';
            $semesterName = $semester->name ?? 'Unknown Semester';
            $className = $class->class_name ?? 'N/A';

            $transcriptData[$yearName][$semesterName][] = [
                'class_name' => $className,
                'score' => number_format($grade->score, 2),
                'status' => $grade->score >= 40 ? 'Passed' : 'Failed',
            ];
        }

        $content = $this->renderPartial('transcript-pdf', [
            'student' => $student,
            'transcriptData' => $transcriptData,
        ]);

        $pdf = new Mpdf();
        $pdf->WriteHTML($content);
        return $pdf->Output('Transcript.pdf', \Mpdf\Output\Destination::DOWNLOAD);
    }
}
