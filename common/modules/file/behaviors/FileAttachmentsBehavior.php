<?php

namespace common\modules\file\behaviors;

use common\modules\file\models\File;
use common\modules\file\models\Placeholder;
use common\modules\file\traits\ModuleTrait;
use yii\base\Behavior;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseFileHelper;
use yii\helpers\StringHelper;
use yii\web\UploadedFile;

/**
 * Class FileAttachBehavior
 * @package common\modules\file\behaviors
 *
 * @property ActiveRecord $owner
 */
class FileAttachmentsBehavior extends Behavior
{
    use ModuleTrait;

    /**
     * @var array
     */
    public $attributes = [];

    /**
     * @var array
     */
    protected $_upload = [];

    /**
     * @var array
     */
    protected $_upload_sort = [];

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_AFTER_INSERT => 'saveUploads',
            ActiveRecord::EVENT_AFTER_UPDATE => 'saveUploads',
            ActiveRecord::EVENT_AFTER_DELETE => 'removeFiles',
        ];
    }

    /**
     * @param $attribute
     * @param $absolutePath
     * @param null $originalName
     * @return bool|File
     * @throws Exception
     * @throws \Exception
     */
    public function attachFile($attribute, $absolutePath, $originalName = null)
    {
        if (!file_exists($absolutePath)) {
            throw new Exception('File not exist! :' . $absolutePath);
        }

        if (!$originalName) {
            $originalName = basename($absolutePath);
        }

        if (!$this->owner->primaryKey) {
            throw new Exception('Owner must have primaryKey when you attach file!');
        }

        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        $fileName =
            substr(md5(microtime(true) . $absolutePath), 4, 6)
            . '.' . $extension;

        $subDir = $this->module->getModelSubDir($this->owner, $attribute);
        $storePath = $this->module->getStorePath();

        $newAbsolutePath = $storePath .
            DIRECTORY_SEPARATOR . $subDir .
            DIRECTORY_SEPARATOR . $fileName;

        BaseFileHelper::createDirectory($storePath . DIRECTORY_SEPARATOR . $subDir,
            0775, true);

        copy($absolutePath, $newAbsolutePath);

        if (!file_exists($newAbsolutePath)) {
            throw new \Exception('Cant copy file! ' . $absolutePath . ' to ' . $newAbsolutePath);
        }

        $file = new File();
        $file->model = $this->module->getShortClass($this->owner);
        $file->attribute = $attribute;
        $file->item_id = $this->owner->primaryKey;
        $file->path = $subDir . '/' . $fileName;
        $file->name = $originalName;
        $file->mime_type = BaseFileHelper::getMimeType($newAbsolutePath);
        $file->size = filesize($newAbsolutePath);
        $file->extension = $extension;

        if ($file->getIsImage()) {
            list($width, $height) = getimagesize($newAbsolutePath);
            $file->image_width = $width;
            $file->image_height = $height;
        }

        if (!$file->save()) {
            $this->owner->addErrors($file->errors);
            return false;
        }

        return $file;
    }

    /**
     * @return array
     */
    public function getAttributeNames()
    {
        return ArrayHelper::getColumn($this->attributes,'attribute');
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (StringHelper::endsWith($name, '_upload')) {
            $attribute = str_replace('_upload', '', $name);
            return isset($this->_upload[$attribute]) ? $this->_upload[$attribute] : null;
        } else {
            return parent::__get($name);
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function __set($name, $value)
    {
        if (StringHelper::endsWith($name, '_upload')) {
            $attribute = str_replace('_upload', '', $name);
            $this->_upload[$attribute] = $value;
        } else {
            parent::__set($name, $value);
        }
    }

    /**
     * @param string $name
     * @param bool $checkVars
     * @return bool
     */
    public function canGetProperty($name, $checkVars = true)
    {
        if (StringHelper::endsWith($name, '_upload')) {
            return true;
        }
        return parent::canGetProperty($name, $checkVars);
    }

    /**
     * @param string $name
     * @param bool $checkVars
     * @return bool
     */
    public function canSetProperty($name, $checkVars = true)
    {
        if (StringHelper::endsWith($name, '_upload')) {
            return true;
        }
        return parent::canSetProperty($name, $checkVars);
    }

    /**
     * @return void
     */
    public function beforeValidate()
    {
        foreach ($this->getAttributeNames() as $attributeName) {
            if ($this->getIsMultipleFileAttribute($attributeName)) {
                $this->_upload[$attributeName] = UploadedFile::getInstances($this->owner, $attributeName . '_upload');
            } else {
                $this->_upload[$attributeName] = UploadedFile::getInstance($this->owner, $attributeName . '_upload');
            }
        }
    }

    /**
     * @param $attributeName
     * @return bool
     */
    public function getIsMultipleFileAttribute($attributeName)
    {
        $attributes = ArrayHelper::index($this->attributes, 'attribute');
        return !empty($attributes[$attributeName]['multiple']);
    }

    /**
     * @param $attribute
     * @param bool $placeholder
     * @return array|ActiveRecord[]|File[]
     */
    public function getFiles($attribute, $placeholder = false)
    {
        $files = $this->getFileFinder($attribute)->all();
        if (!$files && $placeholder && $this->module->placeHolderPath !== false) {
            array_push($files, new Placeholder());
        }
        return $files;
    }

    /**
     * @param $attribute
     * @param bool $placeholder
     * @return array|null|ActiveRecord|File
     */
    public function getFile($attribute, $placeholder = false)
    {
        $file = $this->getFileFinder($attribute)->one();
        if (!$file && $placeholder && $this->module->placeHolderPath !== false) {
            $file = new Placeholder();
        }
        return $file;
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function saveUploads()
    {
        foreach ($this->getAttributeNames() as $attribute) {
            if ($this->getIsMultipleFileAttribute($attribute)) {
                $modelClass = $this->module->getShortClass($this->owner);
                $key = $attribute . '_upload_sort';
                if (isset($_POST[$modelClass][$key])) {
                    foreach ($_POST[$modelClass][$key] as $sort => $file_id) {
                        File::updateAll(['sort' => $sort], ['id' => $file_id]);
                    }
                }
                foreach ($this->_upload[$attribute] as $file) {
                    $this->attachFile($attribute, $file->tempName, $file->baseName . '.' . $file->extension);
                }
            } else {
                if (isset($this->_upload[$attribute])) {
                    $existingFile = $this->getFile($attribute);
                    if ($existingFile) {
                        $existingFile->delete();
                    }
                    $this->attachFile($attribute, $this->_upload[$attribute]->tempName, $this->_upload[$attribute]->baseName . '.' . $this->_upload[$attribute]->extension);
                }
            }
        }
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function removeFiles()
    {
        foreach ($this->getAttributeNames() as $attributeName) {
            $files = $this->getFiles($attributeName);
            foreach ($files as $file) {
                $file->remove();
            }
        }
    }

    /**
     * @param array $ids
     * @throws \Throwable
     */
    public function removeFilesByIds($ids)
    {
        foreach ($ids as $id) {
            $file = File::findOne($id);
            if ($file) {
                $file->remove();
            }
        }
    }

    /**
     * @param $attribute
     * @return ActiveQuery
     */
    public function getFileFinder($attribute)
    {
        return File::find()
            ->where([
                'model' => $this->module->getShortClass($this->owner),
                'attribute' => $attribute,
                'item_id' => $this->owner->primaryKey,
            ])->orderBy('sort');
    }
}