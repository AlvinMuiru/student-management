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
    $student = Yii::$app->user->identity->student;

    if (!$student) {
        throw new \yii\web\NotFoundHttpException('Student profile not found.');
    }

    $semester = \app\components\SemesterHelper::getCurrentSemester();
    $semesterId = $semester ? $semester->id : null;

    $grades = \app\models\Grade::find()
        ->where(['student_id' => $student->id])
        ->joinWith('class')
        ->andWhere(['classes.semester_id' => $semesterId])
        ->all();

    return $this->render('index', [
        'grades' => $grades,
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
