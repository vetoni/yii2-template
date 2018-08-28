<?php

namespace common\modules\file\models;

/**
 * Class Placeholder
 * @package common\modules\file\models
 */
class Placeholder extends File
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%file}}';
    }

    /**
     * @return bool|string
     */
    public function getStorePath()
    {
        return dirname($this->module->getPlaceHolderPath());
    }

    /**
     * @param bool $absolute
     * @return string
     */
    public function getOriginalUrl($absolute = false)
    {
        return $this->module->placeHolderUrl;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return basename($this->module->getPlaceHolderPath());
    }

    /**
     * @return bool
     */
    public function getIsImage()
    {
        return true;
    }
}