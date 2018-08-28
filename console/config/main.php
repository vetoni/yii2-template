<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
        'migrate'=>[
            'class' => console\controllers\MigrateController::class,
            'migrationLookup' => [
                '@yii/rbac/migrations',
                '@vendor/lajax/yii2-translate-manager/migrations',
                '@vendor/pheme/yii2-settings/migrations',
                '@common/modules/user/migrations',
                '@common/modules/file/migrations',
                '@common/modules/catalog/migrations',
            ]
        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params' => $params,
];
