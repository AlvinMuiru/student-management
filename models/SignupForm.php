<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Students;
use app\models\Teacher;


class SignupForm extends Model
{
    public $username;
    public $password;
    public $role;

    public function rules()
    {
        return [
            [['username', 'password', 'role'], 'required'],
            ['username', 'string', 'min' => 4],
            ['password', 'string', 'min' => 6],
            ['role', 'in', 'range' => ['admin', 'teacher', 'student']],
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            Yii::warning('Signup validation failed: ' . json_encode($this->getErrors()));
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->role = $this->role;

       Yii::info("User created: ID = {$user->id}, Role = {$user->role}", __METHOD__);

        if ($user->save()) {
            // Log user creation
            Yii::info('User created: ID=' . $user->id . ', Role=' . $user->role);

            // Assign RBAC role
            $auth = Yii::$app->authManager;
            $role = $auth->getRole($user->role);
            if ($role) {
                $auth->assign($role, $user->id);
                Yii::info('RBAC role assigned: ' . $user->role);
            } else {
                Yii::warning('RBAC role not found: ' . $user->role);
            }

            // If the role is student, create student record
            if ($user->role === 'student') {
                 Yii::info("Creating student record for user ID = {$user->id}", __METHOD__);
                $student = new Students();
                $student->user_id = $user->id;
                $student->first_name = $user->username;
                $student->last_name = 'unknown'; // optional

                Yii::info('Attempting to save student for user_id=' . $user->id);

                if ($student->save()) {
                    Yii::info('Student record created for user_id=' . $user->id);
                } else {
                    Yii::error('Failed to save student: ' . json_encode($student->getErrors()));
                }
            }

            // If the role is teacher, create teacher record
if ($user->role === 'teacher') {
    
        $teacher = new \app\models\Teacher();
        $teacher->user_id = $user->id;
        $teacher->first_name = $user->username;  // You can update later
        $teacher->last_name = '';                // Optional
        $teacher->email = '';                    // Optional
        $teacher->phone = '';                    // Optional

        if (!$teacher->save()) {
            Yii::error('Failed to create teacher: ' . json_encode($teacher->getErrors()), __METHOD__);
        } else {
            Yii::info("Teacher record created for user ID = {$user->id}", __METHOD__);
        }
    
}



            return $user;
        } else {
            Yii::error('User save failed: ' . json_encode($user->getErrors()));
        }

        return null;
    }
}
