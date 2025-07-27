<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class ExamInvigilator extends ActiveRecord
{
    public static function tableName()
    {
        return 'exam_invigilators';
    }

    public function rules()
    {
        return [
            [['exam_schedule_id', 'teacher_id'], 'required'],
            [['exam_schedule_id', 'teacher_id'], 'integer'],
        ];
    }

    public function getExam()
    {
        return $this->hasOne(ExamSchedule::class, ['id' => 'exam_schedule_id']);
    }

    public function getTeacher()
    {
        return $this->hasOne(Teacher::class, ['id' => 'teacher_id']);
    }
}
