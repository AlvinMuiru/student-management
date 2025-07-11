<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Teacher;
use yii\web\NotFoundHttpException;

class TeacherDashboardController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'update'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // logged-in users
                        'matchCallback' => function () {
                            return Yii::$app->user->identity->role === 'teacher';
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $teacher = Teacher::find()->where(['user_id' => Yii::$app->user->id])->one();

        if (!$teacher) {
            throw new NotFoundHttpException('Teacher profile not found.');
        }

        return $this->render('index', [
            'teacher' => $teacher,
            'classes' => $teacher->classes
        ]);
    }

    public function actionUpdate()
    {
        $teacher = Teacher::find()->where(['user_id' => Yii::$app->user->id])->one();

        if (!$teacher) {
            throw new NotFoundHttpException('Teacher profile not found.');
        }

        if ($teacher->load(Yii::$app->request->post()) && $teacher->save()) {
            Yii::$app->session->setFlash('success', 'Profile updated!');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'teacher' => $teacher
        ]);
    }
}
