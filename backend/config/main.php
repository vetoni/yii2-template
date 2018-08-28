<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'as access' => [
        'class' => yii\filters\AccessControl::class,
        'rules' => [
            [
                'actions' => ['login', 'error'],
                'allow' => true,
            ],
            [
                'allow' => true,
                'roles' => ['admin'],
            ],
        ],
    ],
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'backend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl' => '/admin',
        ],
        'user' => [
            'identityClass' => common\modules\user\models\User::class,
            'loginUrl' => ['user/management/login'],
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'app-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
    'homeUrl' => '/admin',
    'modules' => [
        'file' => [
            'class' => common\modules\file\Module::class
        ],
        'settings' => [
            'class' => common\modules\settings\Module::class,
        ],
        'user' => [
            'class' => common\modules\user\Module::class,
        ],
        'translatemanager' => [
            'class' => lajax\translatemanager\Module::class,
            'allowedIPs' => ['*'],
            'root' => ['@common', '@frontend', '@backend'],
            'scanRootParentDirectory' => false,
            'layout' => '@backend/views/layouts/main',
            'ignoredItems' => ['runtime', 'assets'],
            'roles' => ['admin']
        ],

    ],
    'params' => $params,
];
