<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Students;
use app\models\Grade;
use yii\web\NotFoundHttpException;

class TranscriptController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'pdf'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // only logged-in users
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->redirect(['transcript/pdf']);
    }

    public function actionPdf()
    {
        $userId = Yii::$app->user->id;
        $student = Students::find()->where(['user_id' => $userId])->one();

        if (!$student) {
            throw new NotFoundHttpException("Student record not found.");
        }

        // Get all grades for the student that are tied to a semester
        $grades = Grade::find()
            ->joinWith(['class.semester.academicYear'])
            ->where(['grades.student_id' => $student->id])
            ->andWhere(['IS NOT', 'classes.semester_id', null])
            ->all();

        $transcriptData = [];

        foreach ($grades as $grade) {
            $class = $grade->class;
            $semester = $class->semester ?? null;
            $academicYear = $semester->academicYear ?? null;

            if (!$semester || !$academicYear) {
                continue;
            }

            $yearName = $academicYear->year_name;
            $semesterName = $semester->name;

            $transcriptData[$yearName][$semesterName][] = [
                'class_name' => $class->class_name ?? 'N/A',
                'score' => $grade->score,
                'status' => $grade->passed ? 'Passed' : 'Failed',
            ];
        }

        return $this->render('transcript-pdf', [
            'student' => $student,
            'transcriptData' => $transcriptData,
        ]);
    }
}
