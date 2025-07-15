<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "retake_requests".
 *
 * @property int $id
 * @property int $grade_id
 * @property int $student_id
 * @property string|null $status
 * @property string|null $created_at
 */
class RetakeRequest extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'retake_requests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => 'pending'],
            [['grade_id', 'student_id'], 'required'],
            [['grade_id', 'student_id'], 'integer'],
            [['created_at'], 'safe'],
            [['status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'grade_id' => 'Grade ID',
            'student_id' => 'Student ID',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

}
