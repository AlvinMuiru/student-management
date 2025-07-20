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
        $paidCount = StudentFee::find()
            ->alias('sf')
            ->innerJoin('students s', 's.id = sf.student_id')
            ->where(['s.course_id' => $course->id, 'sf.status' => 'paid'])
            ->count();

        $unpaidCount = StudentFee::find()
            ->alias('sf')
            ->innerJoin('students s', 's.id = sf.student_id')
            ->where(['s.course_id' => $course->id, 'sf.status' => 'unpaid'])
            ->count();

        $paidData[] = ['course' => $course->name, 'count' => (int)$paidCount];
        $unpaidData[] = ['course' => $course->name, 'count' => (int)$unpaidCount];
    }

    return $this->asJson([
        'paid' => $paidData,
        'unpaid' => $unpaidData,
    ]);
}

}
