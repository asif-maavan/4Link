<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'site/login',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'KZyEos7erBPobEuYCxj00QBSP6hUNXQ-',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\common\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // Disable index.php
            'showScriptName' => false,
            // Disable r= routes
            'enablePrettyUrl' => true,
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => (YII_ENV == 'dev' ? 'box1160.bluehost.com' : 'mail.sunlineadmin.com'),
                'username' => (YII_ENV == 'dev' ? 'sunline@maavan.com' : 'sunline@sunlineadmin.com'),
                'password' => (YII_ENV == 'dev' ? 'sUnL!ne431' : '?@Lg?;e@/|Q)J9'),
                'port' => (YII_ENV == 'dev' ? '465' : '26'),
                'encryption' => (YII_ENV == 'dev' ? 'ssl' : ''),
            ],
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
        //'db' => require(__DIR__ . '/db.php'),
        'mongodb' => require(__DIR__ . '/mongodb.php'),
    ],
//    'modules' => [
//        'sunline' => [
//            'class' => 'app\modules\sunline\SunLine',
//        ],
//        'sellmileage' => [
//            'class' => 'app\modules\sellmileage\SellMileage',
//        ],
//    ],
    
    'params' => [
        'secretKeyCc' => 'cR3d!tC@rD612',
        'pageSize' => 10,
        'siteRoot' => realpath(dirname(__FILE__)),
    ],
//    'as beforeRequest' => [
//        'class' => 'yii\filters\AccessControl',
//        'rules' => [
//            [
//                'allow' => true,
//                'actions' => ['login', 'error'],
//            ],
//            [
//                'allow' => true,
//                'roles' => ['@'],
//            ],
//        ],
//        'denyCallback' => function () {
//    return Yii::$app->user->loginRequired(); //Yii::$app->response->redirect(['site/login']);
//},
//    ],
];

if (YII_ENV_DEV) {
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
