<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
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
        return null;
    }

    $user = new User();
    $user->username = $this->username;
    $user->setPassword($this->password);
    $user->generateAuthKey();
    $user->role = $this->role;

    if ($user->save()) {
        // Assign RBAC role
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($user->role);
        if ($role) {
            $auth->assign($role, $user->id);
        }

        // ✅ Ensure student record is created
      if ($user->role === 'student') {
    Yii::info("Creating student record for user_id: " . $user->id, __METHOD__);

    $student = new Students();
    $student->user_id = $user->id;
    $student->first_name = $user->username; // or from a field in the signup form
    $student->last_name = 'LastName';        // placeholder or form field
    $student->reg_no = 'REG-' . time();      // must be unique, so use timestamp
    // Optional: default values
    $student->email = null;
    $student->birthdate = null;
    $student->address = null;

    if (!$student->save()) {
        Yii::error("Failed to create student: " . json_encode($student->getErrors()), __METHOD__);
    } else {
        Yii::info("Student record created for user_id: " . $user->id, __METHOD__);
    }
}

        // ✅ Optional: Create teacher record
        if ($user->role === 'teacher') {
            $teacher = new Teacher();
            $teacher->user_id = $user->id;
            $teacher->first_name = $user->username;
            $teacher->last_name = 'LastName'; 

            if (!$teacher->save()) {
                Yii::error("Failed to create teacher: " . json_encode($teacher->getErrors()), __METHOD__);
            }
        }

        return $user;
    }

    return null;
}

}
