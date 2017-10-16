<?php

namespace bilginnet\plupload;


use yii\web\AssetBundle;
use yii\web\View;

class PluploadAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';
    public $jsOptions = ['position' => View::POS_END];

    public $js = [
        'plupload.full.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
