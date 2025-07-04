<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "attendance".
 *
 * @property int $id
 * @property int $student_id
 * @property int $class_id
 * @property string $date
 * @property string $status
 */
class Attendance extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attendance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_id', 'class_id', 'date', 'status'], 'required'],
            [['student_id', 'class_id'], 'integer'],
            [['date'], 'safe'],
            [['status'], 'string', 'max' => 10],
        ];
    }
      
    public function behaviors()
{
    return [
        [
            'class' => TimestampBehavior::class,
            'createdAtAttribute' => 'created_at',
            'updatedAtAttribute' => 'updated_at',
            'value' => new \yii\db\Expression('NOW()'),
        ],
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
            'class_id' => 'Class ID',
            'date' => 'Date',
            'status' => 'Status',
        ];
    }

     public function getStudent()
{
    return $this->hasOne(Students::className(), ['id' => 'student_id']);
}

public function getClass()
{
    return $this->hasOne(ClassModel::className(), ['id' => 'class_id']);
}


}
