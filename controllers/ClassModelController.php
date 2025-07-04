<?php

namespace app\controllers;
use app\models\Students;
use app\models\ClassAssignment; 
use app\models\ClassModel;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Teacher; 
use yii\helpers\ArrayHelper; 
use Yii; 

/**
 * ClassController implements the CRUD actions for ClassModel model.
 */
class ClassModelController extends Controller
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
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
            'query' => ClassModel::find(),
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
        $teachers = Teacher::find()->all();
        $allStudents = Students::find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
                Yii::$app->session->setFlash('error', 'Class saved but enrollments failed: '.$e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
            'teachers' => ArrayHelper::map($teachers, 'id', function($teacher) {
                return $teacher->first_name . ' ' . $teacher->last_name;
            }),
            'allStudents' => ArrayHelper::map($allStudents, 'id', function($student) {
                return $student->first_name . ' ' . $student->last_name;
            })
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $teachers = Teacher::find()->all();
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
            'teachers' => ArrayHelper::map($teachers, 'id', function($teacher) {
                return $teacher->first_name . ' ' . $teacher->last_name;
            }),
            'allStudents' => ArrayHelper::map($allStudents, 'id', function($student) {
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
        $class = $this->findModel($id);
        $allStudents = Students::find()->all();

        if (Yii::$app->request->isPost) {
            $selectedStudents = Yii::$app->request->post('students', []);

            $transaction = Yii::$app->db->beginTransaction();
            try {
                ClassAssignment::deleteAll(['class_id' => $id]);

                foreach ($selectedStudents as $studentId) {
                    $enrollment = new ClassAssignment([
                        'class_id' => $id,
                        'student_id' => $studentId,
                        'date_assigned' => date('Y-m-d')
                    ]);
                    $enrollment->save();
                }

                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Students enrolled successfully!');
                return $this->redirect(['view', 'id' => $id]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Enrollment failed: '.$e->getMessage());
            }
        }

        return $this->render('enroll', [
            'class' => $class,
            'allStudents' => ArrayHelper::map($allStudents, 'id', function($student) {
                return $student->first_name . ' ' . $student->last_name;
            }),
            'currentStudents' => ArrayHelper::getColumn($class->students, 'id')
        ]);
    }
}
