<?php

namespace common\components;

use Yii;
use yii\helpers\Url;

/**
 * Class ActionColumn
 * @package common\components
 */
class ActionColumn extends \yii\grid\ActionColumn
{
    /**
     * @param string $action
     * @param \yii\db\ActiveRecordInterface $model
     * @param mixed $key
     * @param int $index
     * @return string
     */
    public function createUrl($action, $model, $key, $index)
    {
        $params = is_array($key) ? $key : ['id' => (string) $key, 'return_url' => Yii::$app->request->url];
        $controller = Yii::$app->controller->id;
        $params[0] = $controller ? $controller . '/' . $action : $action;
        return Url::toRoute($params);
    }
}