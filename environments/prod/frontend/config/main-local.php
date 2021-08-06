<?php

$redisPort = getenv('REDIS_PORT') !== false ? getenv('REDIS_PORT') : 6379;
$redisDb = getenv('REDIS_DATABASE') !== false ? getenv('REDIS_DATABASE') : 0;

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

# Include Sentry event logger
if (getenv('SENTRY_DSN')) {

    $sentryOptions = [];
    if (getenv('SENTRY_RELEASE')) {
        $sentryOptions['release'] = getenv('SENTRY_RELEASE');
    }
    if (getenv('SENTRY_ENVIRONMENT')) {
        $sentryOptions['environment'] = getenv('SENTRY_ENVIRONMENT');
    }

    $config['components']['log'] = [
        'targets' => [
            [
                'class' => 'notamedia\sentry\SentryTarget',
                'dsn' => getenv('SENTRY_DSN'),
                'levels' => ['error', 'warning'],
                // Write the context information (the default is true):
                'context' => true,
                // Additional options for `Sentry\init`:
                'clientOptions' => $sentryOptions,
            ],
        ],
    ];
}

return $config;
