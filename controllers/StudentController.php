<?php

namespace app\controllers;

use Yii;
use app\models\Students;
use app\models\ClassAssignment;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class StudentController extends Controller
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['index', 'view', 'create', 'update', 'delete', 'assign-class'],
                    'rules' => [
                        // Allow index and view for admin and student
                        [
                            'allow' => true,
                            'actions' => ['index', 'view'],
                            'roles' => ['@'],
                            'matchCallback' => function () {
                                return in_array(Yii::$app->user->identity->role, ['admin', 'student']);
                            },
                        ],
                        // Allow create, delete, assign-class only for admin
                        [
                            'allow' => true,
                            'actions' => ['create', 'delete', 'assign-class'],
                            'roles' => ['@'],
                            'matchCallback' => function () {
                                return Yii::$app->user->identity->role === 'admin';
                            },
                        ],
                        // Allow update if admin or if student owns the profile
                        [
                            'allow' => true,
                            'actions' => ['update'],
                            'roles' => ['@'],
                            'matchCallback' => function () {
                                $id = Yii::$app->request->get('id');
                                $student = Students::findOne($id);
                                $user = Yii::$app->user->identity;
                                return $user->role === 'admin' || ($student && $student->user_id == $user->id);
                            }
                        ],
                    ],
                ],
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
            'query' => Students::find(),
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
        $model = new Students();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->loadDefaultValues();

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Students::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionAssignClass($id)
    {
        $student = Students::findOne($id); // Fixed class name typo here

        if (!$student) {
            throw new NotFoundHttpException('Student not found');
        }

        $model = new ClassAssignment();
        $model->student_id = $id;
        $model->date_assigned = date('Y-m-d');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->render('assign-class', [
            'student' => $student,
            'model' => $model,
        ]);
    }
}
