<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Invigilator extends ActiveRecord
{
    public static function tableName()
    {
        return 'invigilators';
    }

    public function rules()
    {
        return [
            [['exam_id', 'teacher_id'], 'required'],
            [['exam_id', 'teacher_id'], 'integer'],
        ];
    }

    public function getExam()
    {
        return $this->hasOne(ExamSchedule::class, ['id' => 'exam_id']);
    }

    public function getTeacher()
    {
        return $this->hasOne(Teachers::class, ['id' => 'teacher_id']);
    }
}
