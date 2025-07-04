<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Attendance;

class AttendanceSearch extends Attendance
{
    public $class_name; // Add this for class name filtering
    public $student_name; // Add this for student name filtering

    public function rules()
    {
        return [
            [['id', 'student_id', 'class_id'], 'integer'],
            [['date', 'status', 'class_name', 'student_name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
          $query = Attendance::find()
        ->joinWith(['class', 'student']); // Use relation names defined in your Attendance model

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
    ]);

    // Configure sorting for related fields
    $dataProvider->sort->attributes['class_name'] = [
        'asc' => ['classes.class_name' => SORT_ASC], // Changed from class_model to classes
        'desc' => ['classes.class_name' => SORT_DESC],
    ];
    
    $dataProvider->sort->attributes['student_name'] = [
        'asc' => ['students.first_name' => SORT_ASC, 'students.last_name' => SORT_ASC],
        'desc' => ['students.first_name' => SORT_DESC, 'students.last_name' => SORT_DESC],
    ];

    $this->load($params);

    if (!$this->validate()) {
        return $dataProvider;
    }

    // Standard filters
    $query->andFilterWhere([
        'attendance.id' => $this->id,
        'attendance.student_id' => $this->student_id,
        'attendance.class_id' => $this->class_id,
        'attendance.date' => $this->date,
    ]);

    // Text search filters
    $query->andFilterWhere(['like', 'status', $this->status])
          ->andFilterWhere(['like', 'classes.class_name', $this->class_name]) // Changed from class_model to classes
          ->andFilterWhere(['or',
              ['like', 'students.first_name', $this->student_name],
              ['like', 'students.last_name', $this->student_name]
          ]);

    return $dataProvider;
    }
}