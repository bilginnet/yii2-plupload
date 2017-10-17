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

Let's add in controller file
````php
public function actions() {
    return [
        'plupload' => [
            'class' => \bilginnet\plupload\PluploadAction::className(),
            // upload path - default is '@webroot/uploads' or set your path sample: '@yourpath'
            'targetDir' => '@webroot/uploads',
            'onComplete' => function ($filename, $params) {
                // Do something with file
            }
        ],
    ];
}
````

Let's add in your _form file
````php
<?= \bilginnet\plupload\Plupload::widget([
    'url' => ['plupload'], // sync url name to action name in controller file
    
    // optional unique name of uploader
    // will set automatically if not set
    'uploader' => $uploaderName = uniqid('uploader_'),
    
    // auto start when files selected default true
    // you can set false this if you want to start uploader when form submitting
    /* 
    sample: 
    $('form').on('beforeSubmit', function(event, jqXHR, settings) {
        var form = $(this);
        if(form.find('.has-error').length) {
            return false;
        }
        
        var myUploader = <?= $uploaderName ?>;

        myUploader.bind("UploadComplete", function(uploader, files) {
            $("form").submit();
        });
        myUploader.start();        
    });
    */
    'startOnSelect' => true
    
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

Please refer to the Plupload documentation: http://www.plupload.com/docs/v2/pluploadQueue


Notes
-----
You can use multiple widgets in 1 file 
sample:
````php
<?= \bilginnet\plupload\Plupload::widget($options1); ?>
<?= \bilginnet\plupload\Plupload::widget($options2); ?>
````
