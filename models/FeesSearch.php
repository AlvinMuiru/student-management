<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fee;
use app\models\Courses;
use app\models\StudentFee;


class FeesSearch extends Fee
{
    public $description;

    public function rules()
    {
        return [
            [['id', 'course_id'], 'integer'],
            [['amount'], 'number'],
            [['description', 'created_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Fee::find()->joinWith(['course']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'fee.id' => $this->id,
            'fee.course_id' => $this->course_id,
            'fee.amount' => $this->amount,
            'DATE(fee.created_at)' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'fee.description', $this->description]);

        return $dataProvider;
    }
}
