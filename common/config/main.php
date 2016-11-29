<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-common',
            'cookieValidationKey' => 'ymi',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
         'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=y-mi',
            'username' => 'root',
            'password' => 'root',
            'tablePrefix' => 'ymi_',
            'charset' => 'utf8',
        ],
        /*'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
        */
        'errorHandler' => [
            'errorAction' => '/site/error',
        ],
    ],
    'id' => 'ymi-common',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'debug',
        'gii'
    ],
    'controllerNamespace' => 'common\controllers',
    'defaultRoute' => 'index',
    'modules' => [
        'debug' => [
            'class' => 'yii\debug\Module',
        ],
        'gii' => [
            'class' => 'yii\gii\Module',
        ],
    ],
    //'language' => 'zh',
    //'charset' => 'UTF-8',
    //'timeZone' => 'America/Los_Angeles',
    /*'params' => [
        'thumbnail.size' => [128, 128],
    ],*/
    //'version' => 'v1.0',
    /*'extensions' => [
        [
            'name' => 'extension name',
            'version' => 'version number',
            'bootstrap' => 'BootstrapClassName',  // 可选配，可为配置数组
            'alias' => [  // 可选配
                '@alias1' => 'to/path1',
                '@alias2' => 'to/path2',
            ],
        ],
    ],*/
];