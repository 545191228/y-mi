<?php
require "../BaseInit.php";

// 加载应用配置
require(YMI_COMMON_PATH . '/config/bootstrap.php');
require(YMI_ROOT_PATH . '/admin/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(YMI_COMMON_PATH . '/config/main.php'),
    require(YMI_ROOT_PATH . '/admin/config/main.php')
);

$application = new yii\web\Application($config);
$application->run();