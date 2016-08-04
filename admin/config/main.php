<?php
return [
    'id' => 'ymi-admin',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'admin\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-admin',
        ],
    ],
];