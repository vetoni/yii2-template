<?php

namespace common\modules\file\controllers;

use common\modules\file\models\File;
use common\modules\file\traits\ModuleTrait;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * Class FileController
 * @package common\modules\file\controllers
 */
class FileController extends Controller
{
    use ModuleTrait;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
     * @param $id
     * @return string
     * @throws \Throwable
     */
    public function actionDelete($id)
    {
        $file = File::findOne($id);
        $result = $file ? $file->remove() : false;
        return Json::encode([
            'success' => $result,
        ]);
    }
}