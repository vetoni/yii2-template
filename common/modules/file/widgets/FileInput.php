<?php

namespace common\modules\file\widgets;

use common\modules\file\behaviors\FileAttachmentsBehavior;
use common\modules\file\assets\FileUploadAsset;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;


/**
 * Class FileInput
 * @package common\modules\file\widgets
 *
 * @property FileAttachmentsBehavior|ActiveRecord $model
 */
class FileInput extends \kartik\file\FileInput
{
    /**
     * @var bool
     */
    public $imagesOnly = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $initialPreview = [];
        $initialPreviewConfig = [];

        $fileAttributeName = $this->getFileAttributeName();

        $files = $this->model->getFiles($fileAttributeName);
        $multiple = $this->model->getIsMultipleFileAttribute($fileAttributeName);

        if ($this->imagesOnly) {
            $this->options['accept'] = 'image/*';
            $this->options['data-allowed-file-extensions'] = '["jpg", "jpeg", "gif", "png"]';
        }

        if ($multiple) {
            $this->options['multiple'] = true;
        }

        foreach ($files as $file) {
            $initialPreview[] = $file->getUrl(false, true);
            $configItem = [
                'filename' => $file->name,
                'caption' => $file->name,
                'url' => Url::to(['//file/file/delete', 'id' => $file->id]),
                'key' => $file->id,
                'extra' => ['sort' => $file->sort],
                'filetype' => $file->mime_type
            ];
            $icon = $file->getFileIcon();
            if ($icon) {
                $configItem['type'] = $icon;
            }
            $initialPreviewConfig[] = $configItem;
        }

        $sortInputName = Html::getInputName($this->model, $fileAttributeName . '_upload_sort[]');

        $this->pluginOptions = [
            'showUpload' => false,
            'showRemove' => false,
            'initialPreview' => $initialPreview,
            'initialPreviewConfig' => $initialPreviewConfig,
            'overwriteInitial' => !$multiple,
            'previewFileIconSettings' => [
                'doc' => '<i class="fa fa-file-word-o text-primary"></i>',
                'xls' => '<i class="fa fa-file-excel-o text-success"></i>',
                'ppt' => '<i class="fa fa-file-powerpoint-o text-danger"></i>',
                'pdf' => '<i class="fa fa-file-pdf-o text-danger"></i>',
                'zip' => '<i class="fa fa-file-archive-o text-muted"></i>',
                'htm' => '<i class="fa fa-file-code-o text-info"></i>',
                'txt' => '<i class="fa fa-file-text-o text-info"></i>',
                'mov' => '<i class="fa fa-file-movie-o text-warning"></i>',
                'mp3' => '<i class="fa fa-file-audio-o text-warning"></i>',
                'jpg' => '<i class="fa fa-file-photo-o text-danger"></i>',
                'gif' => '<i class="fa fa-file-photo-o text-muted"></i>',
                'png' => '<i class="fa fa-file-photo-o text-primary"></i>'
            ],

            'previewFileExtSettings' => [
                'doc' => new \yii\web\JsExpression("function(ext) { return ext.match(/(doc|docx)$/i); }"),
                'xls' => new \yii\web\JsExpression("function(ext) { return ext.match(/(xls|xlsx)$/i); }"),
                'ppt' => new \yii\web\JsExpression("function(ext) { return ext.match(/(ppt|pptx)$/i); }"),
                'zip' => new \yii\web\JsExpression("function(ext) { return ext.match(/(zip|rar|tar|gzip|gz|7z)$/i); }"),
                'htm' => new \yii\web\JsExpression("function(ext) { return ext.match(/(php|js|css|htm|html)$/i); }"),
                'txt' => new \yii\web\JsExpression("function(ext) { return ext.match(/(txt|ini|md)$/i); }"),
                'mov' => new \yii\web\JsExpression("function(ext) { return ext.match(/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i); }"),
                'mp3' => new \yii\web\JsExpression("function(ext) { return ext.match(/(mp3|wav)$/i); }"),
            ],
            'initialPreviewAsData' => true,
        ];

        $this->pluginEvents = [
            'filesorted' => new \yii\web\JsExpression("function(e, params) {                                    
                var cont = $(this).closest('.upload-wrap').find('.sorter').html('');                
                $.each(params.stack, function(index, value) {
                    cont.append(\"<input type='hidden' value='\" + value.key + \"' name='$sortInputName'>\");
                });                      
            }")
        ];

        echo '<div class="upload-wrap">';
        echo '<div class="sorter"></div>';
        parent::init();
        echo '</div>';
    }

    /**
     * @return string
     */
    protected function getFileAttributeName()
    {
        if (StringHelper::endsWith($this->attribute, '_upload')) {
            return str_replace('_upload', '', $this->attribute);
        } elseif (StringHelper::endsWith($this->attribute, '_upload[]')) {
            return str_replace('_upload[]', '', $this->attribute);
        } else {
            throw new \InvalidArgumentException('Attribute name should end with _upload or _upload[] prefix.');
        }
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        parent::registerAssets();
        FileUploadAsset::register($this->view);
    }
}