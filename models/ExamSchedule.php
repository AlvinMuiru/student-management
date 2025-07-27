<?php

namespace app\models;

use Yii;
use app\models\ClassModel;
use yii\db\ActiveRecord;

class ExamSchedule extends ActiveRecord
{
    public static function tableName()
    {
        return 'exam_schedules';
    }

    public function rules()
    {
        return [
            [['class_id', 'semester_id', 'exam_date', 'start_time', 'end_time', 'venue'], 'required'],
            [['class_id', 'semester_id'], 'integer'],
            [['exam_date', 'start_time', 'end_time'], 'safe'],
            [['venue'], 'string', 'max' => 255],
        ];
    }

    public function getClass()
    {
        return $this->hasOne(ClassModel::class, ['id' => 'class_id']);
    }

    public function getSemester()
    {
        return $this->hasOne(Semester::class, ['id' => 'semester_id']);
    }

    public function getInvigilators()
    {
        return $this->hasMany(ExamInvigilator::class, ['exam_schedule_id' => 'id']);
    }

    public function getAttendances()
    {
        return $this->hasMany(ExamAttendance::class, ['exam_schedule_id' => 'id']);
    }
   
public function getExamName()
{
    return 'Exam on ' . $this->exam_date;
}


}
