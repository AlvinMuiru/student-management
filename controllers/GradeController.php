<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Grade;
use app\models\Semester;
use app\models\forms\AssignGradeForm;
use app\components\SemesterHelper;
use app\models\RetakeRequest;
use app\models\ClassModel;
class GradeController extends Controller
{
    public function actionIndex()
    {
        $student = Yii::$app->user->identity->student;

        if (!$student) {
            throw new NotFoundHttpException('Student profile not found.');
        }

        $semesters = Semester::find()
            ->joinWith('academicYear')
            ->orderBy(['academic_year_id' => SORT_DESC, 'start_date' => SORT_ASC])
            ->all();

        $semesterId = Yii::$app->request->get('semester_id');
        $semester = $semesterId ? Semester::findOne($semesterId) : SemesterHelper::getCurrentSemester();

        if (!$semester) {
            Yii::$app->session->setFlash('warning', 'No active or selected semester found.');
            return $this->render('index', [
                'grades' => [],
                'semesters' => $semesters,
                'selectedSemester' => null,
            ]);
        }

        $grades = Grade::find()
            ->where([
                'student_id' => $student->id,
                'semester_id' => $semester->id,
            ])
            ->with(['class'])
            ->all();

        return $this->render('index', [
            'grades' => $grades,
            'semesters' => $semesters,
            'selectedSemester' => $semester,
            'student' =>$student,
        ]);
    }

    public function actionAssign($studentId, $classId)
    {
        $model = new AssignGradeForm();
        $model->student_id = $studentId;
        $model->class_id = $classId;

        $existingGrade = Grade::findOne(['student_id' => $studentId, 'class_id' => $classId]);
        if ($existingGrade) {
            $model->cat_score = $existingGrade->cat_score;
            $model->exam_score = $existingGrade->exam_score;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Grade assigned successfully.');
            return $this->redirect(['classes/view', 'id' => $classId]); // Or appropriate redirect
        }

        return $this->render('assign', [
            'model' => $model,
        ]);
    }

    public function actionRegisterRetake($id)
    {
        $grade = Grade::findOne($id);
        $studentId = Yii::$app->user->identity->student->id ?? null;

        if (!$grade || $grade->student_id != $studentId) {
            throw new NotFoundHttpException("Grade not found or unauthorized.");
        }

        $existingRequest = RetakeRequest::find()
            ->where(['grade_id' => $id, 'student_id' => $studentId])
            ->one();

        if ($existingRequest) {
            Yii::$app->session->setFlash('warning', 'You have already requested a retake for this class.');
        } else {
            $retake = new RetakeRequest();
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
