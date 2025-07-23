<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use app\models\Grade;
use app\components\SemesterHelper;
use app\models\Semester;

class GradeController extends \yii\web\Controller
{
   
  public function actionIndex()
{
    $student = Yii::$app->user->identity->student;

    if (!$student) {
        throw new NotFoundHttpException('Student profile not found.');
    }

    // Get all semesters
    $semesters = Semester::find()
        ->joinWith('academicYear')
        ->orderBy(['academic_year_id' => SORT_DESC, 'start_date' => SORT_ASC])
        ->all();

    // Check if a semester is selected from the dropdown
    $semesterId = Yii::$app->request->get('semester_id');
    $semester = $semesterId ? Semester::findOne($semesterId) : \app\components\SemesterHelper::getCurrentSemester();

    if (!$semester) {
        Yii::$app->session->setFlash('warning', 'No active or selected semester found.');
        return $this->render('index', [
            'grades' => [],
            'semesters' => $semesters,
            'selectedSemester' => null,
        ]);
    }

    // Fetch grades for this student and selected semester
   $grades = Grade::find()
    ->where([
        'student_id' => $student->id,
        'semester_id' => $semester->id,
    ])
    ->with(['class']) // 👈 add this line
    ->all();


    return $this->render('index', [
        'grades' => $grades,
        'semesters' => $semesters,
        'selectedSemester' => $semester,
    ]);
}

   public function actionRegisterRetake($id)
{
    $grade = Grade::findOne($id);
    $studentId = Yii::$app->user->identity->student->id ?? null;

    if (!$grade || $grade->student_id != $studentId) {
        throw new NotFoundHttpException("Grade not found or unauthorized.");
    }

    // Check if already requested
    $existingRequest = \app\models\RetakeRequest::find()
        ->where(['grade_id' => $id, 'student_id' => $studentId])
        ->one();

    if ($existingRequest) {
        Yii::$app->session->setFlash('warning', 'You have already requested a retake for this class.');
    } else {
        $retake = new \app\models\RetakeRequest();
        $retake->grade_id = $id;
        $retake->student_id = $studentId;
        if ($retake->save()) {
            Yii::$app->session->setFlash('success', 'Retake request submitted successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to submit retake request.');
        }
    }

    return $this->redirect(['grade/index']);
}

}
