<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "class_assignments".
 *
 * @property int $id
 * @property int $student_id
 * @property int $class_id
 * @property string $date_assigned
 */
class ClassAssignment extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'class_assignments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_id', 'class_id', 'date_assigned'], 'required'],
            [['student_id', 'class_id'], 'integer'],
            [['date_assigned'], 'safe'],
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
            'date_assigned' => 'Date Assigned',
        ];
    }

    public function getStudent()
{
    return $this->hasOne(Student::className(), ['id' => 'student_id']);
}

public function getClass()
{
    return $this->hasOne(ClassModel::className(), ['id' => 'class_id']);
}

}
