<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "exam_invigilators".
 *
 * @property int $id
 * @property int $exam_schedule_id
 * @property int $teacher_id
 * @property string|null $assigned_at
 *
 * @property Teacher $teacher
 * @property ExamSchedule $examSchedule
 */
class ExamInvigilator extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'exam_invigilators';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['exam_schedule_id', 'teacher_id'], 'required'],
            [['exam_schedule_id', 'teacher_id'], 'integer'],
            [['assigned_at'], 'safe'],
            [['exam_schedule_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExamSchedule::class, 'targetAttribute' => ['exam_schedule_id' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::class, 'targetAttribute' => ['teacher_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'exam_schedule_id' => 'Exam Schedule',
            'teacher_id' => 'Invigilator (Teacher)',
            'assigned_at' => 'Assigned At',
        ];
    }

    /**
     * Gets the assigned teacher (invigilator).
     */
    public function getTeacher()
    {
        return $this->hasOne(Teacher::class, ['id' => 'teacher_id']);
    }

    /**
     * Gets the associated exam schedule.
     */
    public function getExamSchedule()
    {
        return $this->hasOne(ExamSchedule::class, ['id' => 'exam_schedule_id']);
    }
    
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
