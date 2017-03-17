<?php
namespace home\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.min.css',
    ];
    public $js = [
        'js/jquery-3.1.1.min.js',
        'js/bootstrap.js',
    ];
    public $depends = [
    ];
}