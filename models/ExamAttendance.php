<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class ExamAttendance extends ActiveRecord
{
    public static function tableName()
    {
        return 'exam_attendance';
    }

    public function rules()
    {
        return [
            [['exam_schedule_id', 'student_id', 'status'], 'required'],
            [['exam_schedule_id', 'student_id'], 'integer'],
            [['status'], 'in', 'range' => ['Present', 'Absent']],
        ];
    }

    public function getExam()
    {
        return $this->hasOne(ExamSchedule::class, ['id' => 'exam_schedule_id']);
    }

    public function getStudent()
    {
        return $this->hasOne(Student::class, ['id' => 'student_id']);
    }
    public function getExamSchedule()
{
    return $this->hasOne(ExamSchedule::class, ['id' => 'exam_schedule_id']);
}

}

