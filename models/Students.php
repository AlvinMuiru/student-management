<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "students".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $birthdate
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Students extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'students';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['birthdate', 'address', 'phone', 'email'], 'default', 'value' => null],
            [['first_name', 'last_name'], 'required'],
            [['birthdate', 'created_at', 'updated_at'], 'safe'],
            [['address'], 'string'],
            [['first_name', 'last_name'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 100],
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
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'birthdate' => 'Birthdate',
            'address' => 'Address',
            'phone' => 'Phone',
            'email' => 'Email',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getClassAssignments()
{
    return $this->hasMany(ClassAssignment::className(), ['student_id' => 'id']);
}

public function getClasses()
{
    return $this->hasMany(ClassModel::className(), ['id' => 'class_id'])
        ->viaTable('class_assignments', ['student_id' => 'id']);
}

public function getAttendances()
{
    return $this->hasMany(Attendance::className(), ['student_id' => 'id']);
}

public function getFullName()
{
    return $this->first_name . ' ' . $this->last_name;
}


public function getAttendanceSummary()
{
    $summary = [];
    
    foreach ($this->classes as $class) {
        $total = $this->getAttendances()
            ->where(['class_id' => $class->id])
            ->count();
            
        $present = $this->getAttendances()
            ->where(['class_id' => $class->id, 'status' => 'present'])
            ->count();
            
        $absent = $total - $present;
        $percentage = $total > 0 ? ($present / $total) * 100 : 0;
        
        $summary[] = [
            'class_id' => $class->id,
            'class_name' => $class->class_name,
            'present_count' => $present,
            'absent_count' => $absent,
            'attendance_percentage' => $percentage,
        ];
    }
    
    return $summary;
}
public function getUser()
{
    return $this->hasOne(User::className(), ['id' => 'user_id']);
}
}
