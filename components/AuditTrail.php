<?php

namespace app\components;

use Yii;
use app\models\AuditLog;

class AuditTrail
{
    public static function log($action, $description = null, $model = null, $modelId = null)
    {
        $userId = Yii::$app->user->isGuest ? null : Yii::$app->user->id;

        $log = new AuditLog();
        $log->user_id = $userId;
        $log->action = $action;
        $log->controller = Yii::$app->controller->id ?? null;
        $log->model = $model;
        $log->model_id = $modelId;
        $log->description = $description;
        $log->ip_address = Yii::$app->request->userIP;
        $log->save(false); // Save without validation
    }
    public function getUser()
{
    return $this->hasOne(User::class, ['id' => 'user_id']);
}

}
