<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use app\models\Grade;

class GradeController extends \yii\web\Controller
{
   public function actionIndex()
{
    $studentId = Yii::$app->user->identity->student->id;
    $semester = \app\components\SemesterHelper::getCurrentSemester();

    if (!$semester) {
        Yii::$app->session->setFlash('error', 'No active semester.');
        return $this->render('index', ['grades' => []]);
    }

    // Check if student paid fees for this semester
    $hasPaid = \app\models\StudentFee::find()
        ->where([
            'student_id' => $studentId,
            'semester_id' => $semester->id,
            'status' => 'paid'
        ])->exists();

    if (!$hasPaid) {
        Yii::$app->session->setFlash('warning', 'You must clear fees to view grades for this semester.');
        return $this->render('index', ['grades' => []]);
    }

    $grades = Grade::find()
        ->where([
            'student_id' => $studentId,
            'semester_id' => $semester->id
        ])
        ->with('class')
        ->all();

    return $this->render('index', ['grades' => $grades]);
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
