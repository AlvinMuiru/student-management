<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fee;
use app\models\StudentFee;

/**
 * FeesSearch represents the model behind the search form of `app\models\Fee`.
 */
class FeesSearch extends Fee
{
    public $student_name;
    public $course_name;
    public $receipt_number;
    public $status; 
    public $paid_at; 
    public $description;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'course_id'], 'integer'],
            [['amount'], 'number'],
            [['description', 'created_at', 'student_name', 'course_name', 'receipt_number', 'status'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        
       $query = StudentFee::find()
       ->joinWith(['student', 'fee.course']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
       $query->andFilterWhere(['like', 'student.full_name', $this->student_name])
      ->andFilterWhere(['like', 'course.name', $this->course_name])
      ->andFilterWhere(['like', 'student_fee.receipt_number', $this->receipt_number])
      ->andFilterWhere(['student_fee.status' => $this->status])
      ->andFilterWhere(['DATE(student_fee.paid_at)' => $this->paid_at]);


        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
