<?php
namespace app\models;

use Yii;

/**
 * This is the model class for table "retake_requests".
 *
 * @property int $id
 * @property int $grade_id
 * @property int $student_id
 * @property string|null $status
 * @property string|null $created_at
 *
 * @property Students $student
 * @property Grade $grade
 */
class RetakeRequest extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'retake_requests';
    }

    public function rules()
    {
        return [
            [['grade_id', 'student_id'], 'required'],
            [['grade_id', 'student_id'], 'integer'],
            [['created_at'], 'safe'],
            [['status'], 'string', 'max' => 255],
            [['status'], 'default', 'value' => 'pending'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'grade_id' => 'Grade ID',
            'student_id' => 'Student ID',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    public function getStudent()
    {
        return $this->hasOne(Students::class, ['id' => 'student_id']);
    }

    public function getGrade()
    {
        return $this->hasOne(Grade::class, ['id' => 'grade_id'])->with('class');
    }
}
