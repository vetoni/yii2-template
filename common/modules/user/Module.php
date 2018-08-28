<?php

namespace common\modules\user;

/**
 * Class Module
 * @package common\modules\user
 */
class Module extends \yii\base\Module
{
    /**
     * @var array
     */
    public $adminRoles = ['admin'];

    /**
     * Possible values are: findIdentityByNameOrEmail | findIdentityByName
     * @var string
     */
    public $loginMethod = 'findIdentityByNameOrEmail';
}