<?php

namespace common\widgets;

use zxbodya\yii2\elfinder\TinyMceElFinder;

/**
 * Class Redactor
 * @package backend\widgets
 */
class Redactor extends \zxbodya\yii2\tinymce\TinyMce
{
    /**
     * @var array
     */
    public $options = [
        'class' => 'redactor'
    ];


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->fileManager = [
            'class' => TinyMceElFinder::className(),
            'connectorRoute' => '//el-finder/connector',
        ];
    }

    /**
     * @var array
     */
    public $settings = [
        'language' => 'ru',
        'height' => 380,
        'font_formats' => 'Lato=lato;Alexander=alexander;Ahanit=AMG Anahit',
        'fontsize_formats' => "8pt 10pt 12pt 14pt 16pt 17pt 18pt 19pt 20pt 21pt 22pt 24pt 26pt 28pt 30pt 32pt 34pt 36pt",
        'plugins' => [
            "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons template textcolor paste textcolor colorpicker textpattern"
        ],
        'toolbar1' => "newdocument | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
        'toolbar2' => "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
        'toolbar3' => "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft | responsivefilemanager",
        'image_advtab' => true,
        'relative_urls' => false,
        'spellchecker_languages' => "+Русский=ru",
        'convert_urls' => false
    ];
}