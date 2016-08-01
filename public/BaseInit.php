<?php
define('ROOT_DIR' , dirname(__DIR__));
define('VENDOR_DIR' , ROOT_DIR . '/vendor');
define('PUBLIC_DIR' , ROOT_DIR . '/public');
define('COMMON_DIR' , ROOT_DIR . '/common');

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

// 注册 Composer 自动加载器
require(VENDOR_DIR . '/autoload.php');
// 包含 Yii 类文件
require(VENDOR_DIR . '/yiisoft/yii2/Yii.php');