Yii2 extended app template
===================

Initiate project
-------------------

````
composer install
php init
````

Run this only on clean database
-------------------

````
php yii migrate
php yii auth/init
````

Admin password
-------------------
````
admin\admin
````

How to use file module
-------------------

Add module section to the config file

````
'modules' => [
    'file' => [
        'class' => \common\modules\file\Module::className()
    ],
]
````

By default it is configured to use @uploads and @images aliases. 
They should be set in common\config\bootstrap.php like this:

````
Yii::setAlias('@uploads', dirname(dirname(__DIR__)) . '/frontend/web/uploads');
Yii::setAlias('@images', dirname(dirname(__DIR__)) . '/frontend/web/images');
````


Add behavior to your model

````
public function behaviors()
    {
        return [
            'image' => [
                'class' => FileAttachmentsBehavior::className(),
                'attributes' => [
                    ['attribute' => 'image', 'multiple' => false]
                ]
            ],
        ];
    }
````

Note: You can set key 'multiple' to specify multiple file uploads for current property.

Add validation rules to your model

````
public function rules()
{
    return [
        ['image_upload', 'required'],
        ['image_upload', 'image'],
    ];
} 
````

In your edit model form view file add such code:

````
<?= $form->field($model, 'image_upload')->widget(\common\modules\file\widgets\FileUpload::className(), ['imagesOnly' => true]) ?>
````

How to get image url:

````
$model->getFile('image')->getUrl()
````
How to get image thumb url:

````
$model->getFile('image')->getThumb('270x300')
````

How to get images array:

````
$model->getFiles('images');
````

How to add placeholder if result is empty:

````
$model->getFiles('images', true);
````