<?php

namespace common\modules\settings\traits;

use common\modules\settings\Module;
use Exception;

/**
 * Trait ModuleTrait
 * @package common\modules\settings\traits
 *
 * @property Module $module
 */
trait ModuleTrait
{
    /**
     * @return Module|\yii\base\Module
     * @throws Exception
     */
    protected function getModule()
    {
        return \Yii::$app->getModule('settings');
    }
}