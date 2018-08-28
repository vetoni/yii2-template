<?php

$defaultLanguage = 'ru';

return [
    'name' => 'Yii2 extended template',
    'language' => $defaultLanguage,
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'authManager' => [
            'class' => yii\rbac\DbManager::class
        ],
        'cache' => [
            'class' => yii\caching\FileCache::class,
        ],
        'formatter' => [
            'class' => yii\i18n\Formatter::class,
            'dateFormat' => 'dd.MM.yyyy',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'locale' => $defaultLanguage,
            'nullDisplay' => '',
        ],
        'i18n' => [
            'translations' => [
                'yii' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@yii/messages',
                    'sourceLanguage' => 'en',
                ],
                'extensions/yii2-settings/settings' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@vendor/pheme/messages',
                ],
                'model' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@common/messages',
                ],
                'language' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@common/messages',
                ],
                '*' =>  [
                    'class' => yii\i18n\DbMessageSource::class,
                    'db' => 'db',
                    'sourceLanguage' => $defaultLanguage,
                    'sourceMessageTable' => '{{%language_source}}',
                    'messageTable' => '{{%language_translate}}',
                    'cachingDuration' => 86400,
                    'enableCaching' => false,
                ],
            ],
        ],
        'settings' => [
            'class' => pheme\settings\components\Settings::class,
            'cache' => false
        ],
    ]
];
