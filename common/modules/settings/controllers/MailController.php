<?php

namespace common\modules\settings\controllers;

use common\modules\settings\components\SettingsAction;
use common\modules\settings\models\MailSettings;
use common\modules\settings\traits\ModuleTrait;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Class MailController
 * @package common\modules\settings\controllers
 */
class MailController extends Controller
{
    use ModuleTrait;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => $this->module->adminRoles,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => SettingsAction::className(),
                'modelClass' => MailSettings::className(),
                'viewName' => 'index'
            ],
        ];
    }
}