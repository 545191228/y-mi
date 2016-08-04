<?php
define('YMI_ROOT_PATH' , dirname(__DIR__));
define('YMI_VENDOR_PATH' , YMI_ROOT_PATH . '/vendor');
define('YMI_PUBLIC_PATH' , YMI_ROOT_PATH . '/public');
define('YMI_COMMON_PATH' , YMI_ROOT_PATH . '/common');

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

// 注册 Composer 自动加载器
require(YMI_VENDOR_PATH . '/autoload.php');
// 包含 Yii 类文件
require(YMI_VENDOR_PATH . '/yiisoft/yii2/Yii.php');