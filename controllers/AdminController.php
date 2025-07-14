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

    
}
