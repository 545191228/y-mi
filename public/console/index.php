<?php
require "../BaseInit.php";

// 加载应用配置
require(COMMON_DIR . '/config/bootstrap.php');
require(ROOT_DIR . '/home/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(ROOT_DIR . '/home/config/main.php'),
    require(COMMON_DIR . '/common/config/main.php')
);

// 创建、配置、运行一个应用
$application = new yii\console\Application($config);
$exitCode = $application->run();
exit($exitCode);