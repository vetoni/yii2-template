<?php

namespace common\modules\file\traits;

use common\modules\file\Module;
use Exception;

/**
 * Trait ModuleTrait
 * @package common\modules\file\traits
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
        return \Yii::$app->getModule('file');
    }
}