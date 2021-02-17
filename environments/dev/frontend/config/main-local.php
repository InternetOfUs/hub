<?php

$redisPort = getenv('REDIS_PORT') !== false ? getenv('REDIS_PORT') : 6379;
$redisDb = getenv('REDIS_DATABASE') !== false ? getenv('REDIS_DATABASE') : 1;

$config = [
    'components' => [
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'encryption' => 'tls',
                'host' => $params['email.host'],
                'port' => $params['email.port'],
                'username' => $params['email.from'],
                'password' => $params['email.password'],
            ],
        ],
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

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
