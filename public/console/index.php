<?php
require dirname(__DIR__)."/BaseInit.php";

// 加载应用配置
require(YMI_COMMON_PATH . '/config/bootstrap.php');

$config = require(YMI_ROOT_PATH . '/console/config/main.php');

// 创建、配置、运行一个应用
$application = new yii\console\Application($config);
$exitCode = $application->run();
exit($exitCode);
