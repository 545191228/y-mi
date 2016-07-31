<?php
require "../BaseInit.php";

// 加载应用配置
require(COMMON_DIR . '/config/bootstrap.php');
require(ROOT_DIR . '/home/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(ROOT_DIR . '/home/config/main.php'),
    require(COMMON_DIR . '/common/config/main.php')
);

$application = new yii\web\Application($config);
$application->run();