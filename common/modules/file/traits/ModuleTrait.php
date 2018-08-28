<?php

namespace common\modules\file\traits;

use common\modules\file\Module;
use Exception;

/**
 * Trait ModuleTrait
 * @package common\modules\image
 *
 * @property Module $module
 */
trait ModuleTrait
{
    /**
     * @var Module
     */
    private $_module;

    /**
     * @return Module
     * @throws Exception
     */
    protected function getModule()
    {
        if ($this->_module == null) {
            $this->_module = \Yii::$app->getModule('file');
        }

        if(!$this->_module){
            throw new Exception("\n\n\n\n\nFile module not found, may be you didn't add it to your config?\n\n\n\n");
        }

        return $this->_module;
    }
}