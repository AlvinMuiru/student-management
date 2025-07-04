<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "classes".
 *
 * @property int $id
 * @property string $class_name
 * @property string|null $teacher_name
 * @property string|null $schedule
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class ClassModel extends \yii\db\ActiveRecord
{
   public $enrolledStudents;
   
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'classes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
        [['class_name', 'teacher_id'], 'required'],
        [['teacher_id'], 'integer'],
        [['teacher_name', 'schedule'], 'default', 'value' => null],
        [['created_at', 'updated_at'], 'safe'],
        [['class_name'], 'string', 'max' => 50],
        [['teacher_name', 'schedule'], 'string', 'max' => 100],
        ['enrolledStudents', 'each', 'rule' => ['integer']],
    ];
    } 

     public function behaviors()
{
    return [
        [
            'class' => TimestampBehavior::class,
            'createdAtAttribute' => 'created_at',
            'updatedAtAttribute' => 'updated_at',
            'value' => new \yii\db\Expression('NOW()'),
        ],
    ];
}

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        
    return [
        'id' => 'ID',
        'class_name' => 'Class Name',
        'teacher_name' => 'Teacher Name',
        'schedule' => 'Schedule',
        'teacher_id' => 'Teacher',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
    ];
    }

    public function getTeacher()
{
    return $this->hasOne(Teacher::className(), ['id' => 'teacher_id']);
}
  public function getStudents()
{
    return $this->hasMany(Students::className(), ['id' => 'student_id'])
        ->viaTable('class_assignments', ['class_id' => 'id']);
}

}
