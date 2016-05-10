<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
    </head>
    <body style="-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width:100%!important;height:100%;margin:0;padding: 0;">
        <?php $this->beginBody() ?>

        <table style=";margin:0 auto;text-align:inherit;width:100%!important;padding:0;border-bottom:1px solid #e2e2e2;padding-bottom:10px;">
            <tr >
                <td style="text-align: center;">
                    <table class="twelve columns" style="text-align:center;margin:0 auto">
                        <tr style="padding:0;vertical-align:top;text-align:center;margin:0 auto">
                            <td>
                                <?php if($module == 'sunline') { ?>
                                <a href="http://www.sunlinetravels.com"><img src="<?php echo "http://" . $_SERVER["HTTP_HOST"] . Yii::$app->request->baseUrl; ?>/images/email-logo.png"></a>
                                <?php } else { ?>
                                <a href="http://www.sellmileage.com"><img src="<?php echo "http://" . $_SERVER["HTTP_HOST"] . Yii::$app->request->baseUrl; ?>/images/logo-mileage.png"></a>
                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table style="width:100%;margin:0;padding-top: 40px;">
            <tr><td><?php echo $message; ?></td></tr>
        </table>

        <table style="margin:0 auto;text-align:inherit;width:100%!important;padding:0;background-color:#6c6968;height:50px;
               font-family: 'Open Sans';max-width:1280px;width:100%;background-color:#6c6968 !important;border-bottom:1px solid #ddd !important;text-align: center !important;color: #FFF !important;font-size:14px !important;margin-top:50px;">
            <tr >
                <td style="text-align:center;">
                    <table class="twelve columns" style="text-align:center;margin:0 auto">
                        <tr style="padding:0;vertical-align:top;text-align:center;font-family:'Open Sans' !important;margin:0 auto;">
                            <td>
                                <?php if($module == 'sunline') { ?>
                                © <?php echo date("Y"); ?> Sunline Travel Inc. All Rights Reserved
                                <?php } else { ?>
                                © <?php echo date("Y"); ?> Sell Mileage Inc. All Rights Reserved
                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
