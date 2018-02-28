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
            // upload path from alias - default is '@webroot/uploads' or set your alias path sample: '@yourpath'
            // set your alias into config in your main-local config file before return[]
            // Yii::setAlias('@yourpath', '@webroot/uploads/'); 
            'targetDir' => '@webroot/uploads',
            'uploadComplete' => function ($filePath, $params) {
                // Do something with file
            }
        ],
    ];
}
````

Let's add in your _form file
````php
<?= \bilginnet\plupload\Plupload::widget([
    'url' => ['plupload'], // sync url name to action name in controller actions
    
    // optional unique name of uploader
    // will set automatically if not set
    'uploader' => $uploaderName = uniqid('uploader_'),
    
    // auto start when files selected default true
    // you can set false this if you want to start uploader when form submitting
    /* 
    sample: 
    $('button[type="submit"]').click(function(event) {
        
        var _form = $('form');
        
        // ajax form validate
        $.ajax({
            type: 'post',
            url: 'ajaxValidateActionUrl', // set your url
            data: _form.serializeArray()
        }).done(function(data) {            
            if (data === 'true') {
                // ajax validate is true
            
                var myUploader = <?= $uploaderName ?>;
                myUploader.bind("UploadComplete", function(uploader, files) {
                    // do something
                    
                    _form.submit();
                });
                myUploader.start();
                        
            } else {
                // ajax validate is false
                _form.submit();
            }
        });
    });
    // in controller ajaxValidateAction
    public function actionAjaxValidate() {
        $model = new Model();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                print_r('true');
            } else {
                print_r('false');
            }
        }    
    }
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
