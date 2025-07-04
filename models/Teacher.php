<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Teacher extends ActiveRecord
{
    public static function tableName()
    {
        return 'teachers';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['first_name', 'last_name'], 'required'],
            [['email'], 'email'],
            [['phone'], 'string', 'max' => 20],
            [['first_name', 'last_name'], 'string', 'max' => 50],
        ];
    }

    public function getClasses()
    {
        return $this->hasMany(ClassModel::className(), ['teacher_id' => 'id']);
    }

   
public function getfullName()
{
    return $this->first_name . ' ' . $this->last_name;
}
}
