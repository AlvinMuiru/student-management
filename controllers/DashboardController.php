<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class DashboardController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->isStudent();
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
{
    $user = Yii::$app->user->identity;
    $student = $user->student;

    if (!$student) {
        throw new ForbiddenHttpException('You are not registered as a student.');
    }

    $semester = \app\components\SemesterHelper::getCurrentSemester(); // ✅ Fetch active semester
    $semesterId = $semester ? $semester->id : null;
   // dd($semester); // or var_dump($semester); exit;


    return $this->render('index', [
        'student' => $student,
        'semesterId' => $semesterId, // ✅ Pass to view
    ]);
}

}