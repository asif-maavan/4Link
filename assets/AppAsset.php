<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'assets/97e3f5ae/themes/smoothness/jquery-ui.css',
        'css/site.css',
        'css/styles.css',
        'toast/toastr.css',
        'css/style.css',
        'css/forms.css',
        'css/calendar.css',
        'css/buttons.css',
        'css/stats.css',
        'assets/9c170179/toolbar.css'
        
    ];
    public $js = [
        'js/myfile.js',
        'assets/24ac6e24/js/bootstrap.min.js',
        'assets/97e3f5ae/jquery-ui.js',
        'toast/toastr.js',
        'assets/9c170179/toolbar.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
