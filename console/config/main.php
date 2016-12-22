<?php
return [
    'id' => 'ymi-console',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'console\controllers',
    'components' => [
        'request' => [
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=y-mi',
            'username' => 'root',
            'password' => 'root',
            'tablePrefix' => 'ymi_',
            'charset' => 'utf8',
        ],
    ],
];