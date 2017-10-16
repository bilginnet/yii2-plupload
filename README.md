# yii2-plupload
Yii2 Plupload queue widget

[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg)](https://php.net/)
[![Latest Stable Version](https://poser.pugx.org/bilginnet/yii2-plupload/v/stable)](https://packagist.org/packages/bilginnet/yii2-plupload)
[![Total Downloads](https://poser.pugx.org/bilginnet/yii2-plupload/downloads)](https://packagist.org/packages/bilginnet/yii2-plupload)
[![Latest Unstable Version](https://poser.pugx.org/bilginnet/yii2-plupload/v/unstable)](https://packagist.org/packages/bilginnet/yii2-plupload)
[![License](https://poser.pugx.org/bilginnet/yii2-plupload/license)](https://packagist.org/packages/bilginnet/yii2-parpluploadser)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist bilginnet/yii2-plupload "dev-master"
```

or add

```
"bilginnet/yii2-plupload": "dev-master"
```
to the require section of your `composer.json` file.


Usage
-----

Let's add in controllers file
````php
public function actions() {
    return [
        'plupload' => [
            'class' => PluploadAction::className(),
            'targetDir' => '@webroot/uploads' // upload path - default is '@webroot/uploads' or set your path
            'onComplete' => function ($filename, $params) {
                // Do something with file
            }
        ],
    ];
}
````

Let's add in your _form file
````php
<?= \backend\components\Plupload::widget([
    'url' => ['ajax-upload'],
    'browseLabel' => 'Upload',
    'browseOptions' => ['id' => 'browse', 'class' => 'btn btn-success'],
    'options' => [
        'multi_selection' => false, // set true for multiple files
        'filters' => [
            'mime_types' => [
                ['title' => 'Image files', 'extensions' => 'jpg,jpeg,png,gif'],                
            ],
        ],
    ],
    'events' => [
        'FilesAdded' => 'function(uploader, files){                            
            $("#browse").button("loading");
        }',
        'FileUploaded' => 'function(uploader, file, response){
            $("#browse").button("reset");
        }',
        'Error' => 'function (uploader, error) {                            
            $("#browse").button("reset");
        }'
    ],
]); ?>
````