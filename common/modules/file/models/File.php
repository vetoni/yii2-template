<?php

namespace common\modules\file\models;

use common\modules\file\traits\ModuleTrait;
use sjaakp\sortable\Sortable;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseFileHelper;
use yii\helpers\StringHelper;
use yii\imagine\Image;

/**
 * Class File
 * @package common\modules\file\models
 *
 * @property string $id
 * @property string $model
 * @property string $attribute
 * @property integer $item_id
 * @property string $path
 * @property string $name
 * @property string $extension
 * @property string $mime_type
 * @property string $sort_group
 * @property integer $size
 * @property integer $image_width
 * @property integer $image_height
 * @property integer $sort
 */
class File extends ActiveRecord
{
    use ModuleTrait;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['model', 'attribute', 'item_id', 'path', 'name'], 'required'],
            [['model', 'attribute', 'path', 'name'], 'string', 'max' => 255],
            [['item_id', 'size', 'image_height', 'image_width'], 'integer']
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => Sortable::class,
                'orderAttribute' => ['sort_group' => 'sort'],
            ],
        ];
    }

    /**
     * @return bool
     */
    public function getIsImage()
    {
        return StringHelper::startsWith($this->mime_type, 'image');
    }

    /**
     * @return bool
     */
    public function getIsVideo()
    {
        return StringHelper::startsWith($this->mime_type, 'video');
    }

    /**
     * @return bool|string
     */
    public function getFileIcon()
    {
        if ($this->getIsImage()) {
            return 'image';
        }
        if ($this->getIsVideo()) {
            return 'video';
        }
        if ($this->mime_type == 'application/pdf') {
            return 'pdf';
        }
        if (ArrayHelper::isIn($this->extension, ['doc', 'docx', 'xls', 'ppt'])) {
            return 'gdocs';
        }
        return false;
    }

    /**
     * @param bool $addWatermark
     * @param bool $absolute
     * @return string
     */
    public function getUrl($addWatermark = false, $absolute = false)
    {
        return $this->getThumb(null, null, $addWatermark, $absolute);
    }

    /**
     * @param null $width
     * @param null $height
     * @param bool $addWatermark
     * @param bool $absolute
     * @return string
     */
    public function getThumb($width = null, $height = null, $addWatermark = false, $absolute = false)
    {
        if (!$this->getIsImage()) {
            return $this->getOriginalUrl($absolute);
        }
        $watermarkPath = \Yii::getAlias($this->module->waterMarkPath);
        $addWatermark = $this->module->enableWatermarks && file_exists($watermarkPath) && $addWatermark;
        $createThumb = (isset($width) || isset($height) || $addWatermark) && $this->getIsImage();

        if (!$createThumb) {
            return $this->getOriginalUrl($absolute);
        }

        $info = pathinfo($this->getPath());
        $fileName = $info['filename'];
        $fileExtension = $info['extension'];

        if (isset($width)) {
            $fileName .= "_w$width";
        }
        if (isset($height)) {
            $fileName .= "_h$height";
        }
        if ($addWatermark) {
            $fileName .= "_wms" . $this->module->waterMarkScale;
            $fileName .= "_wmx" . $this->module->waterMarkOffsetX;
            $fileName .= "_wmy" . $this->module->waterMarkOffsetY;
        }

        $originalPath = $this->getStorePath() . '/' . $this->getPath();
        $relativePath = dirname($this->getPath())  . '/' . $fileName . ($fileExtension ? '.' . $fileExtension : '');
        $thumbnailPath = $this->module->getCachePath() . '/' . $relativePath;

        if (!file_exists($thumbnailPath) && file_exists($originalPath)) {
            BaseFileHelper::createDirectory(dirname($thumbnailPath), 0777, true);

            if (isset($width) || isset($height)) {
                $thumbImage = Image::thumbnail($originalPath, $width, $height);
            } else {
                $thumbImage = Image::getImagine()->open($originalPath);
            }
            if ($addWatermark) {
                $wmMaxWidth = intval($thumbImage->getSize()->getWidth() * $this->module->waterMarkScale);
                $watermark = Image::thumbnail($watermarkPath, $wmMaxWidth, null);
                $thumbImage = Image::watermark($thumbImage, $watermark, [$this->module->waterMarkOffsetX, $this->module->waterMarkOffsetY]);
            }

            $thumbImage->save($thumbnailPath, ['quality' => $this->module->imageQuality]);
        }

        return $this->formatUrl($this->module->fileCacheUrl . '/' . $relativePath, $absolute);
    }

    /**
     * @return false|int
     * @throws \Throwable
     */
    public function remove()
    {
        @unlink($this->module->getStorePath() . '/' . $this->path);
        return $this->delete();
    }

    /**
     * @return string
     */
    protected function getPath()
    {
        return $this->path;
    }

    /**
     * @return bool|string
     */
    protected function getStorePath()
    {
        return $this->module->getStorePath();
    }

    /**
     * @param $url
     * @param $absolute
     * @return string
     */
    public function formatUrl($url, $absolute)
    {
        $hostInfo = $this->module->baseUrl ? $this->module->baseUrl : Yii::$app->request->hostInfo;
        return $absolute ? ($hostInfo . $url) : $url;
    }

    /**
     * @param bool $absolute
     * @return string
     */
    protected function getOriginalUrl($absolute = false)
    {
        $url = $this->module->fileStorageUrl . '/' . $this->getPath();
        return $this->formatUrl($url, $absolute);
    }
}