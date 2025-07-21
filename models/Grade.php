<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "grades".
 *
 * @property int $id
 * @property int $student_id
 * @property int $class_id
 * @property int $score
 * @property boolean $passed
 * @property string $created_at
 */
class Grade extends ActiveRecord
{
    public static function tableName()
    {
        return 'grades';
    }

    public function rules()
    {
        return [
            [['student_id', 'class_id', 'score'], 'required'],
            [['student_id', 'class_id'], 'integer'],
            [['score'], 'number'],
            [['passed'], 'boolean'],
            [['created_at'], 'safe'], // removed updated_at
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => null, // disable updated_at
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function getStudent()
    {
        return $this->hasOne(Students::class, ['id' => 'student_id']);
    }

    public function getClass()
    {
        return $this->hasOne(ClassModel::class, ['id' => 'class_id']);
    }
    public function getSemester()
{
    return $this->hasOne(Semester::class, ['id' => 'semester_id']);
}

}
