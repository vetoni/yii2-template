<?php

namespace common\modules\settings\traits;

use common\modules\user\Module;

/**
 * Trait ModuleTrait
 * @property-read Module $module
 * @package dektrium\user\traits
 */
trait ModuleTrait
{
    /**
     * @return Module
     */
    public function getModule()
    {
        return \Yii::$app->getModule('settings');
    }
}
