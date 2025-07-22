<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

use app\models\ClassModel;
use app\models\ClassAssignment;
use app\models\Students;
use app\models\Teacher;
use app\models\forms\AssignGradeForm;
use app\models\Grade;
use app\models\Courses;
use app\models\Semester;


class ClassModelController extends Controller
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    
      public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ClassModel::find()
             ->where(['semester_id' => Semester::find()->select('id')->where(['status' => 'active'])]),

        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

 public function actionCreate()
{
    $model = new ClassModel();

    $userId = Yii::$app->user->id;
    $isAdmin = Yii::$app->user->can('admin');

    $teachers = $isAdmin
        ? Teacher::find()->all()
        : Teacher::find()->where(['user_id' => $userId])->all();

    // ✅ Fetch all courses
    $courses = Courses::find()->all();

    if ($model->load(Yii::$app->request->post())) {

        // 👇 Debug if saving fails
        if (!$model->save()) {
            Yii::error($model->getErrors(), 'classModelErrors');
            echo "<pre>";
            print_r($model->getErrors());
            echo "</pre>";
            exit;
        }

        $enrolledStudents = Yii::$app->request->post('ClassModel')['enrolledStudents'] ?? [];

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($enrolledStudents as $studentId) {
                $enrollment = new ClassAssignment([
                    'class_id' => $model->id,
                    'student_id' => $studentId,
                    'date_assigned' => date('Y-m-d')
                ]);
                if (!$enrollment->save()) {
                    throw new \Exception('Failed to save enrollment');
                }
            }
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Class created with student enrollments!');
            return $this->redirect(['view', 'id' => $model->id]);
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Class saved but enrollments failed: ' . $e->getMessage());
        }
    }

    return $this->render('create', [
        'model' => $model,
        'teachers' => ArrayHelper::map($teachers, 'id', function ($teacher) {
            return $teacher->first_name . ' ' . $teacher->last_name;
        }),
        'courses' => ArrayHelper::map($courses, 'id', 'course_name'),
        'allStudents' => [] // students shown after save
    ]);
}

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $userId = Yii::$app->user->id;
        $isAdmin = Yii::$app->user->can('admin');

        $teachers = $isAdmin
            ? Teacher::find()->all()
            : Teacher::find()->where(['user_id' => $userId])->all();

        $allStudents = Students::find()->all();

        $model->enrolledStudents = ArrayHelper::getColumn($model->students, 'id');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $enrolledStudents = Yii::$app->request->post('ClassModel')['enrolledStudents'] ?? [];

            $transaction = Yii::$app->db->beginTransaction();
            try {
                ClassAssignment::deleteAll(['class_id' => $model->id]);

                foreach ($enrolledStudents as $studentId) {
                    $enrollment = new ClassAssignment([
                        'class_id' => $model->id,
                        'student_id' => $studentId,
                        'date_assigned' => date('Y-m-d')
                    ]);
                    if (!$enrollment->save()) {
                        throw new \Exception('Failed to save enrollment');
                    }
                }

                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Class updated with student enrollments!');
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Update failed: ' . $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'teachers' => ArrayHelper::map($teachers, 'id', function ($teacher) {
                return $teacher->first_name . ' ' . $teacher->last_name;
            }),
            'allStudents' => ArrayHelper::map($allStudents, 'id', function ($student) {
                return $student->first_name . ' ' . $student->last_name;
            })
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = ClassModel::findOne(['id' => $id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

   public function actionEnroll($id)
{
    if (!Yii::$app->user->can('admin')) {
        throw new \yii\web\ForbiddenHttpException("Only admins can enroll students.");
    }

    $class = $this->findModel($id);
    $semesterId = $class->semester_id;

    // Get students in the same course who are NOT already enrolled in another class in this semester
    $enrolledStudentIds = ClassAssignment::find()
        ->select('student_id')
        ->leftJoin('classes', 'classes.id = class_assignments.class_id')
        ->where(['classes.semester_id' => $semesterId])
        ->column();

    $students = Students::find()
        ->where(['course_id' => $class->course_id])
        ->andWhere(['not in', 'id', $enrolledStudentIds]) // prevent double enrollment in same semester
        ->all();

    $allStudents = [];
    foreach ($students as $student) {
        $allStudents[$student->id] = $student->first_name . ' ' . $student->last_name . ($student->reg_no ? " ({$student->reg_no})" : '');
    }

    $currentStudents = ClassAssignment::find()
        ->select('student_id')
        ->where(['class_id' => $id])
        ->column();

    if (Yii::$app->request->isPost) {
        $selectedStudents = Yii::$app->request->post('students', []);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            ClassAssignment::deleteAll(['class_id' => $id]);

            foreach ($selectedStudents as $studentId) {
                $assignment = new ClassAssignment();
                $assignment->class_id = $id;
                $assignment->student_id = $studentId;
                $assignment->date_assigned = date('Y-m-d');
                $assignment->save();
            }

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Students enrolled successfully.');
            return $this->redirect(['view', 'id' => $id]);
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Failed to enroll students.');
        }
    }

    return $this->render('enroll', [
        'class' => $class,
        'allStudents' => $allStudents,
        'currentStudents' => $currentStudents,
    ]);
}

public function actionAssignGrade($classId)
{
    $class = $this->findModel($classId);

    if (!Yii::$app->user->can('admin') && $class->teacher_id !== Yii::$app->user->identity->teacher->id) {
        throw new \yii\web\ForbiddenHttpException("You are not allowed to assign grades for this class.");
    }

    $form = new AssignGradeForm();
    $students = $class->students;

    if ($form->load(Yii::$app->request->post()) && $form->validate()) {
        $grade = new Grade();
        $grade->student_id = $form->student_id;
        $grade->class_id = $classId;
        $grade->score = $form->score;
        $grade->passed = $form->score >= 50 ? 1 : 0;

        // ✅ SET THE SEMESTER ID FROM CLASS MODEL
        $grade->semester_id = $class->semester_id;

        if ($grade->save()) {
            Yii::$app->session->setFlash('success', '✅ Grade assigned successfully.');
            return $this->redirect(['view', 'id' => $classId]);
        } else {
            Yii::error($grade->getErrors(), 'grade_save_error');
            Yii::$app->session->setFlash('error', '❌ Failed to save grade: ' . json_encode($grade->getErrors()));
        }
    }

    return $this->render('assign-grade', [
        'model' => $form,
        'class' => $class,
        'students' => $students,
    ]);
}

public function actionAvailable()
{
    $student = Yii::$app->user->identity->student;

    // Get the current active semester
    $activeSemester = Semester::find()->where(['status' => 'active'])->one();

    if (!$activeSemester) {
        Yii::$app->session->setFlash('error', 'No active semester found.');
        return $this->redirect(['site/index']);
    }

    // Find only classes that match the student's course and current semester
    $classes = ClassModel::find()
        ->where(['course_id' => $student->course_id])
        ->andWhere(['semester_id' => $activeSemester->id])
        ->all();

    return $this->render('available', [
        'classes' => $classes,
    ]);
}

}
