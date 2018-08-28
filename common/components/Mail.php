<?php

namespace common\components;

use Yii;
use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 * @package common\components
 */
class Mail implements BootstrapInterface
{
    /**
     * @param \yii\base\Application $app
     * @throws \yii\base\InvalidConfigException
     */
    public function bootstrap($app)
    {
        $settings = Yii::$app->get('settings');
        $host = $settings->get('smtp_host', 'MailSettings');
        $username = $settings->get('smtp_username', 'MailSettings');
        $password = $settings->get('smtp_password', 'MailSettings');
        $port = $settings->get('smtp_port', 'MailSettings');
        $encryption = $settings->get('smtp_encryption', 'MailSettings');
        $useFileTransport = $settings->get('smtp_usefiletransport', 'MailSettings');

        Yii::$app->set('mailer', [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => $host,
                'username' => $username,
                'password' => $password,
                'port' => $port,
                'encryption' => $encryption,
            ],
            'useFileTransport' => (boolean)$useFileTransport,
        ]);
    }
}