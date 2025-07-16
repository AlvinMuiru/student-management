<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\StudentFee;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use app\models\Fee;
use Mpdf\Mpdf;


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

    // Get fees for the student's course
    $courseFees = Fee::find()->where(['course_id' => $courseId])->all();

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
            $studentFee->save(false); // skip validation for now
        }
    }

    $dataProvider = new \yii\data\ActiveDataProvider([
        'query' => StudentFee::find()->where(['student_id' => $studentId])->with('fee.course'),
    ]);

    return $this->render('index', [
        'dataProvider' => $dataProvider,
    ]);
}



   public function actionReceipt($id)
{
    $student = \app\models\Students::findOne(['user_id' => Yii::$app->user->id]);
    $studentFee = StudentFee::findOne(['id' => $id, 'student_id' => $student->id]);

    if (!$studentFee || $studentFee->status !== 'paid') {
        throw new \yii\web\NotFoundHttpException('Receipt unavailable.');
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

    // For demo purposes: just mark as paid (in production, redirect to payment gateway)
    $studentFee->status = 'paid';
    $studentFee->amount_paid = $studentFee->fee->amount;
    $studentFee->paid_at = date('Y-m-d H:i:s');
    $studentFee->save(false);

    Yii::$app->session->setFlash('success', 'Payment successful!');
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


}
