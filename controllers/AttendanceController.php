<?php

namespace app\controllers;

use Yii;
use app\models\Attendance;
use app\models\AttendanceSearch;
use app\models\ClassModel;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class AttendanceController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new AttendanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($class_id = null)
    {
   Yii::error('ATTENDANCE DEBUG: class_id from parameter = ' . var_export($class_id, true));
    $class_id = $class_id ?? Yii::$app->request->get('class_id');
    Yii::error('ATTENDANCE DEBUG: class_id after fallback = ' . var_export($class_id, true));

    if (!$class_id) {
        throw new \yii\web\BadRequestHttpException('No class ID provided.');
    }
  
        // Step 1: Check if class_id is provided
        if (!$class_id) {
            Yii::$app->session->setFlash('error', 'No class ID provided.');
            return $this->redirect(['class-model/index']);
        }

        // Step 2: Load the class model
        $class = ClassModel::findOne($class_id);
        if (!$class) {
            throw new NotFoundHttpException('The requested class does not exist.');
        }

        // Step 3: Get enrolled students via junction table (make sure getStudents() is defined in ClassModel)
        $students = $class->students;

        // Step 4: If no students are enrolled, redirect
        if (empty($students)) {
            Yii::$app->session->setFlash('warning', 'No students enrolled in this class.');
            return $this->redirect(['class-model/view', 'id' => $class_id]);
        }

        // Step 5: Load existing attendance for the day
        $existingAttendance = Attendance::find()
            ->where([
                'class_id' => $class_id,
                'date' => date('Y-m-d')
            ])
            ->indexBy('student_id')
            ->all();

        // Step 6: Build attendance models
        $models = [];
        foreach ($students as $student) {
            $models[] = $existingAttendance[$student->id] ?? new Attendance([
                'student_id' => $student->id,
                'class_id' => $class_id,
                'date' => date('Y-m-d'),
                'status' => 'present'
            ]);
        }

        // Step 7: Handle form submission
        if (Yii::$app->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (Attendance::loadMultiple($models, Yii::$app->request->post())) {
                    $allValid = true;
                    foreach ($models as $model) {
                        if (!$model->validate()) {
                            $allValid = false;
                            break;
                        }
                    }

                    if ($allValid) {
                        foreach ($models as $model) {
                            $model->save(false);
                        }
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Attendance saved successfully!');
                        return $this->redirect(['class-model/view', 'id' => $class_id]);
                    } else {
                        Yii::$app->session->setFlash('error', 'Please correct the attendance errors.');
                    }
                }
                $transaction->rollBack();
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
            }
        }

        return $this->render('create', [
            'class' => $class,
            'models' => $models,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
        if (($model = Attendance::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
