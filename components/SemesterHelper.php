<?php
namespace app\components;

use app\models\Semester;

class SemesterHelper
{
    public static function getCurrentSemester()
    {
        return Semester::find()
            ->where(['<=', 'start_date', date('Y-m-d')])
            ->andWhere(['>=', 'end_date', date('Y-m-d')])
            ->one();
    }
}
