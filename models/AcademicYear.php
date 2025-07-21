<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "academic_years".
 *
 * @property int $id
 * @property string $year_name
 * @property string $start_date
 * @property string $end_date
 * @property string $status
 * @property string $created_at
 *
 * @property Semester[] $semesters
 */
class AcademicYear extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'academic_years';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['year_name', 'start_date', 'end_date', 'status'], 'required'],
            [['start_date', 'end_date', 'created_at'], 'safe'],
            [['year_name', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'year_name' => 'Academic Year Name',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets related semesters
     */
    public function getSemesters()
    {
        return $this->hasMany(Semester::class, ['academic_year_id' => 'id']);
    }
}
