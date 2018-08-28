<?php

namespace common\modules\file\assets;

use yii\web\AssetBundle;

/**
 * Class FileUploadAsset
 * @package common\modules\file\assets
 */
class FileUploadAsset extends AssetBundle
{
    /**
     * ImagePreviewAsset constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->sourcePath = __DIR__ . '/../dist';
    }

    /**
     * @var array
     */
    public $css = [
        'css/upload.css'
    ];
    /**
     * @var array
     */
    public $js = [

    ];
    /**
     * @var array
     */
    public $depends = [
        'yii\jui\JuiAsset'
    ];
}