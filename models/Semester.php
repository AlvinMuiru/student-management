<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "semesters".
 *
 * @property int $id
 * @property int $academic_year_id
 * @property string $name
 * @property string $start_date
 * @property string $end_date
 * @property string $status
 *
 * @property AcademicYear $academicYear
 */
class Semester extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'semesters';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['academic_year_id', 'name', 'start_date', 'end_date', 'status'], 'required'],
            [['academic_year_id'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['name', 'status'], 'string', 'max' => 255],
            [['academic_year_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademicYear::class, 'targetAttribute' => ['academic_year_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'academic_year_id' => 'Academic Year ID',
            'name' => 'Semester Name',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'status' => 'Status',
        ];
    }

    /**
     * Gets related academic year
     */
    public function getAcademicYear()
    {
        return $this->hasOne(AcademicYear::class, ['id' => 'academic_year_id']);
    }
}
