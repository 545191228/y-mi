<?php
return [
    'id' => 'ymi-console',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'console\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-console',
        ],
    ],
];