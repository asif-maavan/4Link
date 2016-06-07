<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Forgot');
//$this->registerCssFile('https://fonts.googleapis.com/css?family=Open+Sans:400,700,300');
?>
<style>
    .login-contents{
        min-height: 346px;
    }
</style>
<div class="page-content">
    <div class="login-container">
        <div class="text-center">
            <a href="<?=Yii::$app->urlManager->createUrl("/")?>"><img src="<?= Yii::$app->request->baseUrl . '/' ?>images/logo.png" alt="" class="img-cust-responsive"></a>
        </div>

        <div class="login-contents">
            <h1>Forgot password</h1>

            <?php if ($flash = Yii::$app->session->getFlash('Forgot-success')) { ?>

                <div class="alert alert-success">
                    <p><?= $flash ?></p>
                </div>

            <?php } else { ?>

                <?php $form = ActiveForm::begin([]); //'id' => 'login-form' ?>
                <?=
                $form->field($model, 'email', ['inputOptions' => ['placeholder' => 'Email', 'class' => 'login-txtbx email-img', 'label' => '',]])->label(false);
                ?>
                <div><?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => ['login-btn'], 'name' => 'login-button']) ?></div>
                <?php ActiveForm::end(); ?>
            <?php } ?>
        </div>

    </div>

</div>


