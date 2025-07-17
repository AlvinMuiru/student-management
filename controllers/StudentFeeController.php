<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\StudentFee;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use app\models\Fee;
use Mpdf\Mpdf;
use app\models\FeesSearch;


class StudentFeeController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // authenticated
                    ],
                ],
            ],
        ];
    }
     
 public function actionIndex()
{
    $user = Yii::$app->user->identity;

    // Ensure this user is a student
    if (!$user || !$user->student) {
        throw new \yii\web\ForbiddenHttpException('Access denied');
    }

    $studentId = $user->student->id;
    $courseId = $user->student->course_id;

    // Step 1: Get ONLY the fees assigned to the student's course
    $courseFees = Fee::find()
        ->where(['course_id' => $courseId])
        ->all();

    // Step 2: For each course fee, create a StudentFee entry if not already exists
    foreach ($courseFees as $fee) {
        $exists = StudentFee::find()
            ->where(['student_id' => $studentId, 'fee_id' => $fee->id])
            ->exists();

        if (!$exists) {
            $studentFee = new StudentFee([
                'student_id' => $studentId,
                'fee_id' => $fee->id,
                'status' => 'unpaid',
                'amount_paid' => 0,
                'paid_at' => null,
                'receipt_path' => null,
            ]);
            $studentFee->save(false); // bypass validation for now
        }
    }

    // Step 3: Fetch only student fees that belong to fees for the student's course
    $dataProvider = new \yii\data\ActiveDataProvider([
        'query' => StudentFee::find()
            ->alias('sf')
            ->joinWith(['fee f'])
            ->where(['sf.student_id' => $studentId, 'f.course_id' => $courseId])
            ->with(['fee.course']),
        'pagination' => [
            'pageSize' => 10,
        ],
    ]);

    return $this->render('index', [
        'dataProvider' => $dataProvider,
    ]);
}

public function actionReceipt($id)
{
    $studentFee = StudentFee::findOne($id);

    if (!$studentFee || $studentFee->status !== 'paid') {
        throw new \yii\web\NotFoundHttpException('Receipt unavailable.');
    }

    // Optional: if role is student, ensure they can only view their own receipt
    if (Yii::$app->user->identity->role === 'student') {
        $student = \app\models\Students::findOne(['user_id' => Yii::$app->user->id]);

        if (!$student || $studentFee->student_id != $student->id) {
            throw new \yii\web\ForbiddenHttpException('Access denied.');
        }
    }

    return $this->renderPartial('receipt', [
        'model' => $studentFee,
    ]);
}

public function actionPay($id)
{
    $studentFee = StudentFee::findOne($id);

    if (!$studentFee || $studentFee->student_id !== Yii::$app->user->identity->student->id) {
        throw new \yii\web\ForbiddenHttpException('You are not allowed to access this payment.');
    }

    if ($studentFee->status === 'paid') {
        Yii::$app->session->setFlash('info', 'This fee is already paid.');
        return $this->redirect(['index']);
    }

    $fee = $studentFee->fee;

    $studentFee->amount_paid = $fee->amount;
    $studentFee->status = 'paid';
    $studentFee->paid_at = date('Y-m-d H:i:s');
    $studentFee->receipt_path = null;

    if ($studentFee->save(false)) { // ✅ Force saving all attributes including receipt_number
        Yii::$app->session->setFlash('success', 'Payment successful.');
    } else {
        Yii::$app->session->setFlash('error', 'Payment failed.');
    }

    return $this->redirect(['index']);
}


public function actionDelete($id)
{
    $fee = $this->findModel($id);

    if (StudentFee::find()->where(['fee_id' => $id])->exists()) {
        Yii::$app->session->setFlash('error', 'Cannot delete this fee. It has payment records.');
        return $this->redirect(['index']);
    }

    $fee->delete();
    return $this->redirect(['index']);
}
public function actionDownloadReceipt($id)
{
    $student = Yii::$app->user->identity->student;
    $studentFee = StudentFee::findOne(['id' => $id, 'student_id' => $student->id]);

    if (!$studentFee || $studentFee->status !== 'paid') {
        throw new \yii\web\NotFoundHttpException('Receipt unavailable.');
    }

    $content = $this->renderPartial('receipt', ['model' => $studentFee]);

     $mpdf = new Mpdf();
     $mpdf->WriteHTML($content);
     return $mpdf->Output("receipt_{$id}.pdf", 'D'); 
}
public function actionAdminLogs()
{
    $searchModel = new FeesSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams, '');

    return $this->render('admin-logs', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]);
}

}
