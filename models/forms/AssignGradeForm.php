<?php

namespace app\models\forms;

use yii\base\Model;

class AssignGradeForm extends Model
{
    public $student_id;
    public $score;

    public function rules()
    {
        return [
            [['student_id', 'score'], 'required'],
            ['score', 'integer', 'min' => 0, 'max' => 100],
        ];
    }
}
