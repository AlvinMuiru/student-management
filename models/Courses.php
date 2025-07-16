<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Courses extends ActiveRecord
{
    public static function tableName()
    {
        return 'courses';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['name'], 'unique'],
        ];
    }

    public function getStudents()
    {
        return $this->hasMany(Students::class, ['course_id' => 'id']);
    }

    public function getClasses()
    {
        return $this->hasMany(ClassModel::class, ['course_id' => 'id']);
    }
}
