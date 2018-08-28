<?php

namespace common\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class Language
 * @package common\models
 */
class Language extends ActiveRecord
{
    /**
     * Inactive status
     */
    const STATUS_INACTIVE = 0;

    /**
     * Active status
     */
    const STATUS_ACTIVE = 1;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%language}}';
    }

    /**
     * @return Language[]
     */
    public static function getList()
    {
        return static::findAll(['status' => self::STATUS_ACTIVE]);
    }

    /**
     * @return array
     */
    public static function getCodes()
    {
        return ArrayHelper::getColumn(static::getList(), 'language');
    }
}