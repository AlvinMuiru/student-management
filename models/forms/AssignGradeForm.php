<?php

namespace app\models\forms;

use yii\base\Model;
use app\models\Grade;

class AssignGradeForm extends Model
{
    public $student_id;
    public $class_id;
    public $cat_score;
    public $exam_score;
    public $score;

    public function rules()
    {
        return [
            [['student_id', 'class_id', 'cat_score', 'exam_score'], 'required'],
            [['student_id', 'class_id'], 'integer'],
            ['cat_score', 'number', 'min' => 0, 'max' => 30], // ✅ out of 30
            ['exam_score', 'number', 'min' => 0, 'max' => 70], // ✅ out of 70
        ];
    }

    public function save()
    {
        $grade = Grade::findOne([
            'student_id' => $this->student_id,
            'class_id' => $this->class_id,
        ]);

        if (!$grade) {
            $grade = new Grade();
            $grade->student_id = $this->student_id;
            $grade->class_id = $this->class_id;
        }

        // ✅ No normalization or weighting
        $grade->cat_score = $this->cat_score;
        $grade->exam_score = $this->exam_score;
        $grade->score = round($this->cat_score + $this->exam_score); // ✅ total out of 100

        $grade->passed = $grade->score >= 40;

        return $grade->save();
    }
}
