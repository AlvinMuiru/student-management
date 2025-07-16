<?php

namespace app\models;
use yii\db\ActiveRecord;

use Yii;

/**
 * This is the model class for table "student_fees".
 *
 * @property int $id
 * @property int $student_id
 * @property int $fee_id
 * @property float $amount_paid
 * @property string|null $payment_date
 * @property string|null $receipt_path
 */
class StudentFee extends ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student_fees';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['receipt_path'], 'default', 'value' => null],
            [['student_id', 'fee_id', 'amount_paid'], 'required'],
            [['student_id', 'fee_id'], 'integer'],
            [['amount_paid'], 'number'],
            [['paid_at'], 'safe'],
            [['status'], 'string', 'max' => 20],
            [['receipt_path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => 'Student ID',
            'fee_id' => 'Fee ID',
            'amount_paid' => 'Amount Paid',
            'payment_date' => 'Payment Date',
            'receipt_path' => 'Receipt Path',
        ];
    }

    public function getStudent()
{
    return $this->hasOne(Students::class, ['id' => 'student_id']);
}

public function getFee()
{
    return $this->hasOne(Fee::class, ['id' => 'fee_id']);
}

public static function assignFeesToStudent($studentId)
{
    $student = \app\models\Students::findOne($studentId);
    if (!$student || !$student->course_id) {
        Yii::error("No student or course found for ID: $studentId", __METHOD__);
        return;
    }

    $existingFeeIds = self::find()
        ->select('fee_id')
        ->where(['student_id' => $studentId])
        ->column();

    $fees = \app\models\Fee::find()
        ->where(['course_id' => $student->course_id])
        ->andWhere(['NOT IN', 'id', $existingFeeIds])
        ->all();

    foreach ($fees as $fee) {
        $studentFee = new self();
        $studentFee->student_id = $studentId;
        $studentFee->fee_id = $fee->id;
        $studentFee->status = 'unpaid';
        $studentFee->created_at = date('Y-m-d H:i:s');
        if (!$studentFee->save()) {
            Yii::error("Failed to assign fee ID {$fee->id} to student $studentId: " . json_encode($studentFee->errors), __METHOD__);
        }
    }
}



}
