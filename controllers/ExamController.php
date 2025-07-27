<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\models\ExamSchedule;
use app\models\ClassModel;
use app\models\Semester;

class ExamController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // You can replace this with 'teacher', 'admin' using RBAC
                    ],
                ],
            ],
        ];
    }

 public function actionIndex()
{
    $exams = ExamSchedule::find()
        ->with(['class', 'semester']) // eager load to avoid N+1 queries
        ->all();

    return $this->render('index', [
        'exams' => $exams,
    ]);
}

    public function actionCreate()
    {
        $model = new ExamSchedule();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Exam created successfully.');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'classes' => ClassModel::find()->all(),
            'semesters' => Semester::find()->all(),
        ]);
    }

    public function actionView($id)
    {
        return $this->render('index', [
            'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id)
    {
        if (($model = ExamSchedule::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested exam does not exist.');
    }
}
