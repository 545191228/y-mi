<?php
require "../BaseInit.php";

// 加载应用配置
require(YMI_COMMON_PATH . '/config/bootstrap.php');
require(YMI_ROOT_PATH . '/home/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(YMI_COMMON_PATH . '/common/config/main.php'),
    require(YMI_ROOT_PATH . '/home/config/main.php')
);

// 创建、配置、运行一个应用
$application = new yii\console\Application($config);
$exitCode = $application->run();
exit($exitCode);