<?php

namespace console\controllers;

use common\modules\user\models\User;
use Yii;
use yii\console\Controller;

/**
 * Class AuthController
 * @package console\controllers
 */
class AuthController extends Controller
{
    /**
     * Initialize rbac roles
     */
    public function actionInit()
    {
        User::deleteAll();

        $userAdmin = new User();
        $userAdmin->username = 'admin';
        $userAdmin->email = 'admin@example.com';
        $userAdmin->password_hash = Yii::$app->security->generatePasswordHash('admin');
        $userAdmin->auth_key = Yii::$app->security->generateRandomString();
        $userAdmin->status;
        $userAdmin->save();

        $userUser = new User();
        $userUser->username = 'user';
        $userUser->email = 'user@example.com';
        $userUser->password_hash = Yii::$app->security->generatePasswordHash('user');
        $userUser->auth_key = Yii::$app->security->generateRandomString();
        $userUser->status;
        $userUser->save();

        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $admin = $auth->createRole('admin');
        $user = $auth->createRole('user');

        $auth->add($user);
        $auth->add($admin);

        $userAdmin = User::findOne(['username' => 'admin']);
        $auth->assign($admin, $userAdmin->id);

        $userUser = User::findOne(['username' => 'user']);
        $auth->assign($user, $userUser->id);
    }
}