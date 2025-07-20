<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\StudentFee;

class StudentFeeSearch extends StudentFee
{
    public $student_name;
    public $course_name;
    public $receipt_number;

    public function rules()
    {
        return [
            [['id', 'student_id', 'fee_id'], 'integer'],
            [['amount_paid'], 'number'],
            [['receipt_number', 'status', 'paid_at', 'student_name', 'course_name'], 'safe'],
        ];
    }

  public function search($params)
{
    $query = StudentFee::find()
        ->alias('sf')
        ->joinWith(['student s', 'fee f', 'fee.course c']);

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'sort' => ['defaultOrder' => ['paid_at' => SORT_DESC]],
    ]);

    $this->load($params);

    if (!$this->validate()) {
        return $dataProvider;
    }

    // Main filters
    $query->andFilterWhere([
        'sf.id' => $this->id,
        'sf.student_id' => $this->student_id,
        'sf.fee_id' => $this->fee_id,
        'sf.amount_paid' => $this->amount_paid,
        'sf.status' => $this->status,
        'DATE(sf.paid_at)' => $this->paid_at,
    ]);

    // Text-based filters
    $query->andFilterWhere(['like', 'sf.receipt_number', trim($this->receipt_number ?? '')])
      ->andFilterWhere(['like', new \yii\db\Expression("CONCAT(s.first_name, ' ', s.last_name)"), trim($this->student_name ?? '')])
      ->andFilterWhere(['like', 'c.name', trim($this->course_name ?? '')]);

    return $dataProvider;
}

}
