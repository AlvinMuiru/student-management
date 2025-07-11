<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'users'; // match your table name
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key; // this matches your DB column
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

   
public function validatePassword($password)
{
    return Yii::$app->security->validatePassword($password, $this->password_hash);
}


    public function isStudent()
    {
        return $this->role === 'student';
    }
     public function getStudent()
    {
        return $this->hasOne(Students::class, ['user_id' => 'id']);
    }
    public function generateAuthKey()
{
    $this->auth_key = Yii::$app->security->generateRandomString();
}
public function setPassword($password)
{
    $this->password_hash = Yii::$app->security->generatePasswordHash($password);
}


}
