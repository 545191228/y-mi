<?php
return [
    'id' => 'ymi-home',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'home\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-home',
        ],
    ],
];