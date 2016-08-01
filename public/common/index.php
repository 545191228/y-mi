<?php
require "../BaseInit.php";

// 加载应用配置
require(COMMON_DIR . '/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(COMMON_DIR . '/config/main.php')
);

$application = new yii\web\Application($config);
$application->run();