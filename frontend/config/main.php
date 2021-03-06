<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'WeNet Hub',
    'name' => 'WeNet Hub',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'language' => 'en-US',
    'sourceLanguage' => 'en',
    'components' => [
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@frontend/messages',
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        'index' => 'index.php',
                        'error' => 'error.php',
                    ],
                ],
            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'loginUrl' => ['user/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'wenethub',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@runtime/logs/hub.log', # update the name of the logger with your component name
                    'levels' => YII_DEBUG ? ['error', 'warning', 'info', 'trace'] : ['error', 'warning', 'info'], # set the desired levels
                    'categories' => [
                        # fill with categories of interest
                    ],
                    'except' => [],
                    'rotateByCopy' => false,
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'pattern' => 'data/app/<appId>',
                    'route' => 'wenetapp/json-details',
                ],
                [
                    'pattern' => 'data/app/<appId>/developer',
                    'route' => 'wenetapp/developer-list',
                ],
                [
                    'pattern' => 'data/app/<appId>/user',
                    'route' => 'wenetapp/user-list',
                ],
                [
                    'pattern' => 'data/user/<userId>/apps',
                    'route' => 'user/apps-for-user',
                ],
                [
                    'pattern' => 'data/user',
                    'route' => 'user/get-all-user-ids',
                ],

                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'serviceApi' => [
            'class' => 'frontend\components\ServiceApiConnector',
            'baseUrl' => $params['service.api.base.url'],
            'apikey' => $params['hub.apikey'],
        ],
        'incentiveServer' => [
            'class' => 'frontend\components\IncentiveServerConnector',
            'baseUrl' => $params['incentive.server.base.url'],
            'apikey' => $params['hub.apikey'],
        ],
        'taskManager' => [
            'class' => 'frontend\components\TaskManagerConnector',
            'baseUrl' => $params['task.manager.base.url'],
            'apikey' => $params['hub.apikey'],
        ],
        'profileManager' => [
            'class' => 'frontend\components\ProfileManagerConnector',
            'baseUrl' => $params['profile.manager.base.url'],
            'apikey' => $params['hub.apikey'],
        ],
        'loggingComponent' => [
            'class' => 'frontend\components\LoggingComponentConnector',
            'baseUrl' => $params['logging.component.base.url'],
            'apikey' => $params['hub.apikey'],
        ],
        'kongConnector' => [
            'class' => 'frontend\components\KongConnector',
            'internalBaseUrl' => $params['kong.internal.url'],
            'externalBaseUrl' => $params['kong.external.url'],
            'provisionKey' => $params['kong.provision.key'],
        ]
    ],
    'params' => $params,
];
