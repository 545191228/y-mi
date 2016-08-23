<?php
return [
    'id' => 'ymi-wechat',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'wechat\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-wechat',
        ],
    ],
];