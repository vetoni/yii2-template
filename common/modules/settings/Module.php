<?php

namespace common\modules\settings;

/**
 * Class Module
 * @package common\modules\settings
 */
class Module extends \pheme\settings\Module
{
    /**
     * @var array
     */
    public $adminRoles = ['admin'];

    /**
     * @var string
     */
    public $controllerNamespace = 'common\modules\settings\controllers';
}