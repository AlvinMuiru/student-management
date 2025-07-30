<?php
namespace app\models\forms;

use yii\base\Model;

class AssignGradeForm extends Model
{
    public $student_id;
    public $cat_score;
    public $exam_score;
    public $score;
    public $class_id;
    public $exam_schedule_id; // <-- Add this

    public function rules()
    {
        return [
            [['student_id', 'cat_score', 'exam_score', 'class_id'], 'required'],
            [['cat_score', 'exam_score', 'score'], 'number'],
            [['student_id', 'class_id','exam_schedule_id'], 'integer'],
             [['exam_schedule_id'], 'safe'],
        ];
    }
}
