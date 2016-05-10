<?php

if ($_SERVER['SERVER_NAME'] != 'www.sunlineadmin.com') {
// comment out the following two lines when deployed to production
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'dev');
}

//if (strstr($_SERVER['HTTP_USER_AGENT'], "Safari")) {
//    define("BROWSER", "safari");
//} else {
//    define("BROWSER", "default");
//}

if (strstr($_SERVER['REQUEST_URI'], "sunline") || (isset($_SERVER['HTTP_REFERER']) && strstr($_SERVER['HTTP_REFERER'], "sunline")))
    define("MODULE", "sunline");
elseif (strstr($_SERVER['REQUEST_URI'], "sellmileage") || (isset($_SERVER['HTTP_REFERER']) && strstr($_SERVER['HTTP_REFERER'], "sellmileage")))
    define("MODULE", "sellmileage");
else
    define("MODULE", "default");


require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
