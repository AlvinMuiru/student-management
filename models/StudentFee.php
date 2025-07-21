<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "student_fees".
 *
 * @property int $id
 * @property int $student_id
 * @property int $fee_id
 * @property float $amount_paid
 * @property string|null $paid_at
 * @property string|null $created_at
 * @property string|null $receipt_path
 * @property string|null $receipt_number
 * @property string|null $status
 *
 * @property Students $student
 * @property Fee $fee
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
            [['student_id', 'fee_id', 'amount_paid'], 'required'],
            [['student_id', 'fee_id'], 'integer'],
            [['amount_paid'], 'number'],
            [['paid_at', 'created_at','receipt_number'], 'safe'],
            [['status'], 'string', 'max' => 20],
            [['receipt_path','payment_method', 'bank_name', 'transaction_ref'], 'string', 'max' => 255],
            [['receipt_number'], 'string', 'max' => 100],
            [['receipt_path'], 'default', 'value' => null],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => 'Student',
            'fee_id' => 'Fee',
            'amount_paid' => 'Amount Paid',
            'paid_at' => 'Paid At',
            'created_at' => 'Created At',
            'status' => 'Status',
            'receipt_number' => 'Receipt Number',
            'receipt_path' => 'Receipt Path',
        ];
    }

    /**
     * Generate receipt number on first save if not set.
     */
 public function beforeSave($insert)
{
    if (parent::beforeSave($insert)) {
        if (empty($this->receipt_number)) {
            $last = self::find()->orderBy(['id' => SORT_DESC])->one();
            $lastNumber = $last && $last->receipt_number ? (int) str_replace('RCPT-', '', $last->receipt_number) : 0;
            $this->receipt_number = 'RCPT-' . str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        }
        return true;
    }
    return false;
}


    /**
     * Relationship to student.
     */
    public function getStudent()
    {
        return $this->hasOne(Students::class, ['id' => 'student_id']);
    }

    /**
     * Relationship to fee.
     */
    public function getFee()
    {
        return $this->hasOne(Fee::class, ['id' => 'fee_id']);
    }

    /**
     * Assign all missing course fees to a student.
     */
    public static function assignFeesToStudent($studentId)
    {
        $student = Students::findOne($studentId);
        if (!$student || !$student->course_id) {
            Yii::error("No student or course found for ID: $studentId", __METHOD__);
            return;
        }

        $existingFeeIds = self::find()
            ->select('fee_id')
            ->where(['student_id' => $studentId])
            ->column();

        $fees = Fee::find()
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
    public function getSemester()
{
    return $this->hasOne(Semester::class, ['id' => 'semester_id']);
}

}
