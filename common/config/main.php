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
        /*'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
         'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=stay2',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
        */
        'errorHandler' => [
            'errorAction' => 'site/error',
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
    //'params' => $params,
];