<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Login');
//$this->registerCssFile('https://fonts.googleapis.com/css?family=Open+Sans:400,700,300');
?>

<div class="page-content">
    <div class="login-container">
        <div class="text-center">
            <img src="<?=Yii::$app->request->baseUrl . '/'?>images/logo.png" alt="" class="img-cust-responsive">
        </div>
        <div class="login-contents">
            <h1>LogIn</h1>

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
<!--<div><input type="text" class="login-txtbx email-img" value="Email"></div>-->
            <?=
            $form->field($model, 'email', ['inputOptions' => ['placeholder' => 'Email', 'class' => 'login-txtbx email-img', 'label' => '',]])->label(false);
            ?>
<!--            <div><input type="password" class="login-txtbx lock-img" value="Email"></div>-->
            <?=
            $form->field($model, 'password', ['inputOptions' => ['placeholder' => 'Password', 'class' => 'login-txtbx lock-img',]])->passwordInput()->label(false)
            ?>
            <!--<div><input type="text" class="login-txtbx up-down-img" value="Depot"></div>-->
            <div class="forgot-pass"><a href="#">Forgot Password?</a></div>
<!--            <div><a href="#" class="login-btn">Log In</a></div>-->
            <div><?= Html::submitButton(Yii::t('app', 'LogIn'), ['class' => ['login-btn'], 'name' => 'login-button']) ?></div>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
