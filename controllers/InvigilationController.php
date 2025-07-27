<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Invigilator;
use app\models\ExamSchedule;
use app\models\Teachers;

class InvigilationController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'roles' => ['@']],
                ],
            ],
        ];
    }

    public function actionAssign($exam_id)
    {
        $model = new Invigilator();
        $model->exam_id = $exam_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/exam/view', 'id' => $exam_id]);
        }

        $teachers = Teachers::find()->all();

        return $this->render('assign', [
            'model' => $model,
            'teachers' => $teachers,
        ]);
    }
}
