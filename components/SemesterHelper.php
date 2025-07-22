<?php
namespace app\components;

use app\models\Semester;
use Yii;
class SemesterHelper
{
    public static function getCurrentSemester()
{
    $now = date('Y-m-d');

    return Semester::find()
        ->where(['<=', 'start_date', $now])
        ->andWhere(['>=', 'end_date', $now])
        ->one();
}

}
