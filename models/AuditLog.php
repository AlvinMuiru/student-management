<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "audit_logs".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $action
 * @property string|null $controller
 * @property string|null $model
 * @property int|null $model_id
 * @property string|null $description
 * @property string|null $ip_address
 * @property string|null $created_at
 */
class AuditLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'audit_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'model_id'], 'integer'],
            [['action'], 'required'],
            [['description'], 'string'],
            [['created_at'], 'safe'],
            [['action', 'controller', 'model', 'ip_address'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'action' => 'Action',
            'controller' => 'Controller',
            'model' => 'Model',
            'model_id' => 'Model ID',
            'description' => 'Description',
            'ip_address' => 'IP Address',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets related User model
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
