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
        [['user_id', 'first_name'], 'required'], // ❗only first_name required
        [['last_name', 'email', 'phone'], 'safe'], // ✅ allow these to be optional
        [['user_id'], 'unique'],
        [['user_id'], 'integer'],
        [['first_name', 'last_name'], 'string', 'max' => 255],
        [['email'], 'email'],
        [['phone'], 'string', 'max' => 20],
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
public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
