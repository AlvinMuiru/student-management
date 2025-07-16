<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fees".
 *
 * @property int $id
 * @property int $course_id
 * @property float $amount
 * @property string|null $description
 * @property string|null $created_at
 */
class Fee extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fees';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'default', 'value' => null],
            [['course_id', 'amount'], 'required'],
            [['course_id'], 'integer'],
            [['amount'], 'number'],
            [['created_at'], 'safe'],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'course_id' => 'Course ID',
            'amount' => 'Amount',
            'description' => 'Description',
            'created_at' => 'Created At',
        ];
    }
public function getCourse()
{
    return $this->hasOne(Courses::class, ['id' => 'course_id']);
}

public function getStudentFees()
{
    return $this->hasMany(StudentFee::class, ['fee_id' => 'id']);
}

}
