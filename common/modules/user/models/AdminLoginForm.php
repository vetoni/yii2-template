<?php

namespace common\modules\user\models;

use ReflectionClass;
use Yii;

/**
 * Class AdminLoginForm
 * @package common\modules\user\models
 */
class AdminLoginForm extends LoginForm
{
    /**
     * @return string
     */
    public function formName()
    {
        $reflector = new ReflectionClass(LoginForm::class);
        return $reflector->getShortName();
    }

    /**
     * @return array
     */
    public function rules()
    {
        $parentRules = parent::rules();
        return array_merge($parentRules, [
            'adminRoleCheck' => [
                'password',
                function ($attribute) {
                    $user = $this->getUser();
                    if ($user && !$user->getIsAdmin()) {
                        $this->addError($attribute, Yii::t('user', 'Invalid login or password'));
                    }
                }
            ]
        ]);
    }
}