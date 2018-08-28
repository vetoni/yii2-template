<?php

namespace common\modules\user\traits;

use common\modules\user\Module;
use Exception;

/**
 * Trait ModuleTrait
 * @package common\modules\user\traits
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
        return \Yii::$app->getModule('user');
    }
}