<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use app\models\Students;
use app\models\Teacher;
use app\models\ClassModel;
use app\models\Attendance;
use app\models\Courses;
use app\models\StudentFee;

class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

   public function actionIndex()
{
  

    $totalStudents = Students::find()->count();
    $totalTeachers = Teacher::find()->count();
    $totalClasses = ClassModel::find()->count();

    $today = date('Y-m-d');
    $presentCount = Attendance::find()->where(['date' => $today, 'status' => 'present'])->count();
    $absentCount = Attendance::find()->where(['date' => $today, 'status' => 'absent'])->count();
    $lateCount = Attendance::find()->where(['date' => $today, 'status' => 'late'])->count();

    return $this->render('index', [
        'totalStudents' => $totalStudents,
        'totalTeachers' => $totalTeachers,
        'totalClasses' => $totalClasses,
        'presentCount' => $presentCount,
        'absentCount' => $absentCount,
        'lateCount' => $lateCount,
    ]);
}
   // inside AdminController.php

public function actionChartData()
{
    $courses = Courses::find()->all();
    $paidData = [];
    $unpaidData = [];

    foreach ($courses as $course) {
        $students = Students::find()->where(['course_id' => $course->id])->all();
        $paidCount = 0;
        $unpaidCount = 0;

        foreach ($students as $student) {
            $paidFees = StudentFee::find()
                ->where(['student_id' => $student->id, 'status' => 'paid'])
                ->count();

            if ($paidFees >= 2) { // assumes 2 semesters per academic year
                $paidCount++;
            } else {
                $unpaidCount++;
            }
        }

        $paidData[] = ['course' => $course->name, 'count' => $paidCount];
        $unpaidData[] = ['course' => $course->name, 'count' => $unpaidCount];
    }

    return $this->asJson([
        'paid' => $paidData,
        'unpaid' => $unpaidData,
    ]);
}

}
