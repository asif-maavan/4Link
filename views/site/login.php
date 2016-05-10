<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Login');
?>

<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
<div class="login-container">
    <div class="login-logo"><a href="<?= Yii::$app->homeUrl; ?>"><img src="<?php echo Yii::$app->request->baseUrl; ?>/images/logoi_03.png" alt="" /></a></div>
    <div class="login-username">
        <?=
        $form->field($model, 'email', [
            'inputOptions' => [
                'placeholder' => 'Email',
                'class' => 'username-txtbx',
                'label' => '',
    ]])->label(false);
        ?>
    </div>
    <div class="login-username">
        <?=
        $form->field($model, 'password', [
            'inputOptions' => [
            'placeholder' => 'Password',
            'class' => 'username-txtbx',
    ]])->passwordInput()->label(false)
        ?>
    </div>
    <div class="login-username">
        <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => ['login-btn'], 'name' => 'login-button']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
