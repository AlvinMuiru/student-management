<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // ROLES
        $admin = $auth->createRole('admin');
        $teacher = $auth->createRole('teacher');
        $student = $auth->createRole('student');

        $auth->add($admin);
        $auth->add($teacher);
        $auth->add($student);

        // PERMISSIONS
        $manageAll = $auth->createPermission('manageAll');
        $manageAll->description = 'Manage everything';
        $auth->add($manageAll);

        $manageAttendance = $auth->createPermission('manageAttendance');
        $manageAttendance->description = 'Create/update attendance';
        $auth->add($manageAttendance);

        $accessDashboard = $auth->createPermission('accessDashboard');
        $accessDashboard->description = 'Access student dashboard';
        $auth->add($accessDashboard);

        // ASSIGN permissions
        $auth->addChild($admin, $manageAll);
        $auth->addChild($teacher, $manageAttendance);
        $auth->addChild($student, $accessDashboard);

        echo "RBAC roles and permissions have been initialized.\n";
    }
}
