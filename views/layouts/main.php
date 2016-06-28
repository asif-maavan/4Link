<?php
/* @var $this View */
/* @var $content string */

use app\assets\AppAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
?>
<?php
$this->beginPage();
$baseUrl = Yii::$app->request->baseUrl;
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <link rel="shortcut icon" href="<?php echo Yii::$app->getHomeUrl(); ?>images/favicon.png" type="image/x-icon" />
        <title><?= Html::encode($this->title) ?></title>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css'>  
        <?php $this->head() ?>
        <style>

        </style>
    </head>
    <body>
        <script type="text/javascript">
            //var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
            var baseUrl = '<?php echo Url::base(true) . "/"; //"http://" . $_SERVER["HTTP_HOST"] . Yii::$app->request->baseUrl . "/";                            ?>';
            var userType = '<?php echo (isset(Yii::$app->user->identity->user_role) ? Yii::$app->user->identity->user_role : ''); ?>';
            var adminId = '<?php echo (isset(Yii::$app->user->identity->_id) ? Yii::$app->user->identity->_id : ''); ?>';
        </script>
        <?php $this->beginBody() ?>
        <div id="loading" style="display:none;">
            <img id="loading-image" src="<?php echo Yii::$app->request->baseUrl; ?>/images/ajax-loader.gif" alt="Loading..." />
        </div>
        <div class="header">
            <div class="container ">
                <div class="row">
                    <div class="col-md-5 margin_fix">
                        <!-- Logo -->
                        <div class="logo margin_fix">
                            <a href="index.html"><img src="<?= $baseUrl ?>/images/logo.png" width="139" height="47" alt=""/></a>
                        </div>
                    </div>
                    <div class="col-md-4 hdr_user_info">
                        <div class="navbar navbar-inverse" role="banner">
                            <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
                                <ul class="nav navbar-nav">
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php if (Yii::$app->user->identity->profile_picture) { ?><img src="<?= $baseUrl ?>/uploads/<?= Yii::$app->user->identity->profile_picture //user_img.png       ?>" width="32" height="32" alt="User"/><?php } ?><?= (Yii::$app->user->identity->_id) ? Yii::$app->user->identity->first_name : 'Guest' ?><b class="caret"></b></a> 
                                        <ul class="dropdown-menu animated fadeInUp">
                                            <?php if (Yii::$app->user->identity->user_role == \app\common\models\User::ROLE_ADMIN) { ?>
                                                <li><a href="<?= Url::toRoute(['/settings/plans/']); ?>">Settings</a></li>
                                            <?php } else { ?>
                                                <li><a href="<?= Url::toRoute(['/user/my-account']); ?>">Profile</a></li> 
                                            <?php } ?>
                                            <li><a href="<?= Url::toRoute(['/site/logout']); ?>">Logout</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="container">

            <?= $content ?>
        </div>

        <footer>
            <div class="container">

                <div class="copy text-center">
                    Copyright <?php echo date("Y"); ?> <a href='#'>Website</a>
                </div>

            </div>
        </footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
