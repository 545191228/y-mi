<?php
return [
    'id' => 'ymi-common',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'debug',
        'gii'
    ],
    'controllerNamespace' => 'common\controllers',
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
        /*'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=stay2',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],*/
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
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