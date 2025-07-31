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
use app\models\Students;
use yii\helpers\ArrayHelper;
use app\models\ExamAttendance;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class GradeController extends Controller
{
    public function behaviors()
{
    return [
        'access' => [
            'class' => AccessControl::class,
            'only' => ['index', 'assign', 'get-scores'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ],
        'verbs' => [
            'class' => VerbFilter::class,
            'actions' => [
                'get-scores' => ['GET'],
                'delete' => ['POST'],
            ],
        ],
    ];
}
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

    // Get latest grade per class for the student in the selected semester
   $subQuery = (new \yii\db\Query())
    ->select(['MAX(id)'])
    ->from(Grade::tableName())  // ✅ Correct table name 'grades'
    ->where([
        'student_id' => $student->id,
        'semester_id' => $semester->id,
    ])
    ->groupBy('class_id');

$grades = Grade::find()
    ->where(['id' => $subQuery])
    ->with(['class'])
    ->all();
    return $this->render('index', [
        'grades' => $grades,
        'semesters' => $semesters,
        'selectedSemester' => $semester,
        'student' => $student,
    ]);
}

   public function actionAssign($studentId, $classId)
{
    $class = \app\models\ClassModel::findOne($classId);
    $student = \app\models\Students::findOne($studentId);

    if (!$class || !$student) {
        throw new \yii\web\NotFoundHttpException('Class or student not found.');
    }

    if (!$class->semester_id) {
        Yii::$app->session->setFlash('error', 'Semester information is missing for this class.');
        return $this->redirect(['classes/view', 'id' => $classId]);
    }

    // ✅ Check if student has paid semester fees
    $hasPaid = \app\models\StudentFee::find()
        ->where([
            'student_id' => $student->id,
            'semester_id' => $class->semester_id,
            'status' => 'paid',
        ])
        ->exists();

    if (!$hasPaid) {
        Yii::$app->session->setFlash('error', 'This student has not cleared fees for the semester. Grade assignment is blocked.');
        return $this->redirect(['classes/view', 'id' => $classId]);
    }

    // ✅ Check if student attended the exam
    $attended = \app\models\ExamAttendance::find()
        ->where([
            'student_id' => $student->id,
            'class_id' => $class->id,
            'status' => 'present',
        ])
        ->exists();

    if (!$attended) {
        Yii::$app->session->setFlash('error', 'This student was absent for the exam. Grade assignment is blocked.');
        return $this->redirect(['classes/view', 'id' => $classId]);
    }

    // ✅ Prepare form
    $model = new \app\models\forms\AssignGradeForm();
    $model->student_id = $studentId;
    $model->class_id = $classId;

    // ✅ Load existing grade for editing
    $existingGrade = \app\models\Grade::findOne([
        'student_id' => $studentId,
        'class_id' => $classId,
    ]);

    if ($existingGrade) {
        $model->cat_score = $existingGrade->cat_score;
        $model->exam_score = $existingGrade->exam_score;
    }

    // ✅ Handle submission
    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        $finalScore =  $model->cat_score+  $model->exam_score;

        if (!$existingGrade) {
            $existingGrade = new \app\models\Grade();
        }

        $existingGrade->student_id = $studentId;
        $existingGrade->class_id = $classId;
        $existingGrade->cat_score = $model->cat_score;
        $existingGrade->exam_score = $model->exam_score;
        $existingGrade->score = $finalScore;

        if ($existingGrade->save()) {
            \app\components\AuditLogHelper::log(
                $existingGrade->isNewRecord ? 'Assigned Grade' : 'Updated Grade',
                "Grade saved for student ID {$studentId} in class ID {$classId} (CAT: {$model->cat_score}, Exam: {$model->exam_score}, Final: {$finalScore})"
            );

            Yii::$app->session->setFlash('success', 'Grade saved successfully.');
            return $this->redirect(['classes/view', 'id' => $classId]);
        }

        Yii::$app->session->setFlash('error', 'Failed to save the grade.');
    }

    return $this->render('assign', [
        'model' => $model,
        'student' => $student,
        'class' => $class,
    ]);
}


public function actionRegisterRetake($id)
{
    $grade = Grade::findOne($id);

    if (!$grade || $grade->score >= 40 || $grade->student_id != Yii::$app->user->identity->student->id) {
        throw new \yii\web\NotFoundHttpException('Invalid request.');
    }

    $existingRequest = RetakeRequest::find()
        ->where(['grade_id' => $grade->id, 'student_id' => $grade->student_id])
        ->one();

    if ($existingRequest) {
        Yii::$app->session->setFlash('warning', 'You have already requested a retake for this class.');
    } else {
        $request = new RetakeRequest();
        $request->grade_id = $grade->id;
        $request->student_id = $grade->student_id;
        $request->status = 'requested';
        $request->created_at = date('Y-m-d H:i:s');
        if ($request->save()) {
            Yii::$app->session->setFlash('success', 'Retake request submitted successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to submit retake request.');
        }
    }

    return $this->redirect(['retake-request/my-requests']);
}


     public function actionGetScores($studentId, $classId)
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $grade = \app\models\Grade::findOne([
        'student_id' => $studentId,
        'class_id' => $classId,
    ]);

    if ($grade) {
        return [
            'success' => true,
            'cat_score' => $grade->cat_score,
            'exam_score' => $grade->exam_score,
        ];
    }

    return ['success' => false];
}


}
