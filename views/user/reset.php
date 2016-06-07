<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Reset');
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

        <div class = "login-contents">
            <?php if ($flash = Yii::$app->session->getFlash('Forgot-error')) { ?>

                <div class="alert alert-danger">
                    <p><?= $flash ?></p>
                </div>

            <?php } else if ($flash = Yii::$app->session->getFlash('Forgot-success')) { ?>

                <div class="alert alert-success">
                    <p><?= $flash ?></p>
                </div>

            <?php } else { ?>
                <h1>Reset password</h1>

                <?php //if (empty(Yii::$app->session->getAllFlashes())) {?>

                <?php
                $form = ActiveForm::begin(['validationUrl' => Yii::$app->urlManager->createUrl("user/reset-validation"),
                            'enableAjaxValidation' => true,]); //'id' => 'reset-form' 
                ?>
                <?= $form->field($model, 'password', ['inputOptions' => ['placeholder' => 'New Password', 'class' => 'login-txtbx lock-img', 'label' => '', 'value' => '']])->passwordInput()->label(false); ?>

                <?= $form->field($model, 'confirm_password', ['inputOptions' => ['placeholder' => 'Confirm Password', 'class' => 'login-txtbx lock-img', 'label' => '', 'value' => '']])->passwordInput()->label(false) ?>

                <div><?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => ['login-btn'], 'name' => 'login-button']) ?></div>
                <?php ActiveForm::end(); ?>
            <?php } ?>

        </div>

    </div>

</div>


