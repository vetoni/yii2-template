<?php

namespace common\modules\settings\models;

use yii\base\Model;

/**
 * Class MailSettings
 * @package common\models
 */
class MailSettings extends Model
{
    /**
     * @var
     */
    public $smtp_host;

    /**
     * @var
     */
    public $smtp_username;

    /**
     * @var
     */
    public $smtp_password;

    /**
     * @var
     */
    public $smtp_port;

    /**
     * @var
     */
    public $smtp_encryption;

    /**
     * @var
     */
    public $smtp_usefiletransport;


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['smtp_host', 'smtp_username', 'smtp_password', 'smtp_port', 'smtp_encryption'], 'string'],
            [['smtp_usefiletransport'], 'integer']
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'smtp_host' => \Yii::t('settings', 'SMTP хост'),
            'smtp_username' => \Yii::t('settings', 'SMTP пользователь'),
            'smtp_password' => \Yii::t('settings', 'SMTP пароль'),
            'smtp_port' => \Yii::t('settings', 'SMTP порт'),
            'smtp_encryption' => \Yii::t('settings', 'SMTP шифрование'),
            'smtp_usefiletransport' => \Yii::t('settings', 'Тестовый режим (письма сохраняются в папку на сервере)'),
        ];
    }
}