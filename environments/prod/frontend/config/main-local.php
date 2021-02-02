<?php

$redisPort = getenv('REDIS_PORT') !== false ? getenv('REDIS_PORT') : 6379;
$redisDb = getenv('REDIS_DATABASE') !== false ? getenv('REDIS_DATABASE') : 0;

return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'session' => [
            'class' => 'yii\redis\Session',
            'redis' => [
                'hostname' => getenv('REDIS_HOST'),
                'port' => $redisPort,
                'database' => $redisDb,
            ]
        ],
        'redis' => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => getenv('REDIS_HOST'),
                'port' => $redisPort,
                'database' => $redisDb,
            ]
        ],
    ],
];
