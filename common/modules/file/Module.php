<?php

namespace common\modules\file;

use Yii;
use yii\helpers\Inflector;

/**
 * Class Module
 * @package common\modules\file
 */
class Module extends \yii\base\Module
{
    /**
     * @var array
     */
    public $adminRoles = ['admin'];

    /**
     * @var string
     */
    public $fileStoragePath = '@uploads/files';

    /**
     * @var string
     */
    public $fileStorageUrl = '/uploads/files';

    /**
     * @var string
     */
    public $fileCachePath = '@uploads/cache';

    /**
     * @var string
     */
    public $fileCacheUrl = '/uploads/cache';

    /**
     * @var string
     */
    public $placeHolderPath = '@images/placeholder.png';

    /**
     * @var string
     */
    public $placeHolderUrl = '/images/placeholder.png';

    /**
     * @var
     */
    public $waterMarkPath = '@images/watermark.png';

    /**
     * @var
     */
    public $enableWatermarks = true;

    /**
     * @var float
     */
    public $waterMarkScale = 0.1;

    /**
     * @var float
     */
    public $waterMarkOffsetX = 0;

    /**
     * @var float
     */
    public $waterMarkOffsetY = 0;

    /**
     * @var int
     */
    public $imageQuality = 80;

    /**
     * @var float|int
     */
    public $memoryLimit = 32 * 1024 * 1024;

    /**
     * @var
     */
    public $baseUrl = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        \IMagick::setResourceLimit(\IMagick::RESOURCETYPE_MEMORY, $this->memoryLimit);
    }

    /**
     * @return bool|string
     */
    public function getStorePath()
    {
        return Yii::getAlias($this->fileStoragePath);
    }

    /**
     * @return bool|string
     */
    public function getCachePath()
    {
        return Yii::getAlias($this->fileCachePath);
    }

    /**
     * @return bool|string
     */
    public function getPlaceHolderPath()
    {
        return Yii::getAlias($this->placeHolderPath);
    }

    /**
     * @param $model
     * @param $attribute
     * @return string
     */
    public function getModelSubDir($model, $attribute)
    {
        $modelName = $this->getShortClass($model);
        $modelDir = Inflector::pluralize($modelName).'/'. $modelName . '_' . $attribute . '_' . $model->id;
        return $modelDir;
    }

    /**
     * @param $obj
     * @return string
     */
    public function getShortClass($obj)
    {
        if (method_exists($obj, 'getShortName')) {
            return $obj->getShortName();
        }
        $className = get_class($obj);
        if (preg_match('@\\\\([\w]+)$@', $className, $matches)) {
            $className = $matches[1];
        }
        return $className;
    }
}