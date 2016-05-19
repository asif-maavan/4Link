<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use \app\common\models\User;
use yii\widgets\ActiveForm;
use app\models\Categories;

$this->title = 'User Management';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/js/user.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$baseUrl = Yii::$app->request->baseUrl . '/';
?>
<style>
    .upload-img input{
        display: inline-block;
        color: #233872 !important;
        margin-top: 43px !important;
        float: left;
        font-size: 16px;
        line-height: 16px;
        text-decoration: underline !important;
        font-family: 'Open Sans', sans-serif;
        margin: 0px 0 0 22px;
    }
    .upload-img .help-block{
        clear: both;
        /*margin-top: 43px !important;*/
        margin: 4px 0 0 22px;
        float: left;
    }
    .custom-file-input::before {
        content: 'Upload';
    }
</style>
<!--<script src="<?php echo Yii::$app->request->baseUrl; ?>/js/user.js"></script>-->
<div class="row">
    <div class="col-md-2 leftbar ">
        <div class="sidebar content-box" style="display: block; ">
            <div  class="back_btn">
                <a href="<?php echo Yii::$app->request->referrer; ?>"><img src="<?= $baseUrl ?>images/back.png" width="43" height="12" alt=""/></a>
            </div>
            <ul class="nav">
                <!-- Main menu -->
                <?php if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                    ?><li><a href="<?= Yii::$app->urlManager->createUrl("settings/plans/"); ?>">Plans</a></li> <?php } ?>
                <li class="select"><a href="<?= Yii::$app->urlManager->createUrl("user/my-account"); ?>">My Account</a></li>
                <?php if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                    ?> <li><a href="<?= Yii::$app->urlManager->createUrl("user/"); ?>">Users</a></li> <?php } ?>
                <li><a href="<?= Yii::$app->urlManager->createUrl("settings/values/"); ?>">Values</a></li>
            </ul>
        </div>
    </div>
    <!-- right side content -->
    <div class="col-md-10 content_wraper">
        <div class="content-box-large3">
            <div class="account-contents">
                <div class="camp-info"> My Account</div>
                <?php
                $form = ActiveForm::begin([//'action' => Yii::$app->urlManager->createUrl("user/create"),
                            'fieldConfig' => ['template' => "{input}{error}"],
                            'validationUrl' => Yii::$app->urlManager->createUrl("user/update-validation"),
                            'enableAjaxValidation' => true,
                            //'enableClientValidation' => true,
                            'options' => [ 'class' => '', 'enctype' => 'multipart/form-data']]);
                ?>
                <div class="row">
                    <div class="col-lg-2 col-md-3 col-sm-3">
                        <div class="upload-img"><img id="prifile-image" class="img-responsive" src="<?= $baseUrl ?><?= ($profilePic)? 'uploads/'.$profilePic: 'images/user-img1.png' ?>" alt="Image only"></div>
                    </div>
                    <div class="col-lg-10 col-md-9 col-sm-9">
                        <div class="upload-img"><a href="javascript:;" onclick="$('#userform-profile_picture').click();">Upload Logo</a></div>
                        <div class="upload-img" for="userform-profile_picture" style="float:left; margin: 43px 0 0 22px;" id="filename"></div>
                        <?= $form->field($model, 'profile_picture', ['options' => ['class' => 'upload-img'], 'inputOptions' => [ 'class'=>'custom-file-input', 'style' => 'display:none;', 'onChange' => "imagePreview(this);$('#filename').html($(this).val()); "]])->fileInput()->label(false); ?>
                    </div>

                </div>
                <div class="row row-margin-t" style="margin-top: 40px;">
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="school-name">First:</div>
                    </div>
                    <div class="col-lg-4 col-md-3 col-sm-3">
<!--                        <div><input type="text" class="school-name-txtbx"></div>-->
                        <?= $form->field($model, 'first_name', ['options' => ['class' => ''], 'inputOptions' => ['class' => 'school-name-txtbx']])->textInput() ?>
                    </div>
                </div>
                <div class="row row-margin-t">
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="school-name">Last:</div>
                    </div>
                    <div class="col-lg-4 col-md-3 col-sm-3">
                        <!--<div><input type="text" class="school-name-txtbx"></div>-->
                        <?= $form->field($model, 'last_name', ['options' => ['class' => ''], 'inputOptions' => ['class' => 'school-name-txtbx']])->textInput() ?>
                    </div>
                </div>                
                <div class="row row-margin-t">
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="school-name">Role:</div>
                    </div>
                    <div class="col-lg-6 col-md-3 col-sm-3">
                        <div class="school-name"><?= $roleList[$model->user_role] ?></div>
                    </div>
                </div>
                <div class="row row-margin-t">
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="school-name">Email:</div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="school-name"><?= $model->email ?></div>
                    </div>
                </div>
                <div class="row row-margin-t">
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="school-name">Phone:</div>
                    </div>
                    <div class="col-lg-4 col-md-3 col-sm-3">
                        <!--<div><input type="text" class="school-name-txtbx"></div>-->
                        <?= $form->field($model, 'phone', ['options' => ['class' => ''], 'inputOptions' => ['class' => 'school-name-txtbx']])->textInput() ?>
                    </div>

                </div>
                <div class="row row-margin-t">
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="school-name">Address:</div>
                    </div>
                    <div class="col-lg-4 col-md-3 col-sm-3">
                        <!--<div><input type="text" class="school-name-txtbx"></div>-->
                        <?= $form->field($model, 'address', ['options' => ['class' => ''], 'inputOptions' => ['class' => 'school-name-txtbx']])->textInput() ?>
                    </div>
                </div>
                <div class="row row-margin-t">
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="school-name">Password:</div>
                    </div>
                    <div class="col-lg-4 col-md-3 col-sm-3">
                        <!--<div><input type="password" class="school-name-txtbx"></div>-->
                        <?= $form->field($model, 'password', ['options' => ['class' => ''], 'inputOptions' => ['class' => 'school-name-txtbx', 'value' => '']])->passwordInput() ?>
                    </div>

                </div>
                <div class="row row-margin-t">
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="school-name">Confirm Password:</div>
                    </div>
                    <div class="col-lg-4 col-md-3 col-sm-3">
                        <!--<div><input type="password" class="school-name-txtbx"></div>-->
                        <?= $form->field($model, 'confirm_password', ['options' => ['class' => ''], 'inputOptions' => ['class' => 'school-name-txtbx', 'value' => '']])->passwordInput() ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <!--<div class="clearfix school-name"><a href="#" class="creat-btn ">Save</a></div>-->
                        <div class="clearfix school-name"><?= Html::submitButton('Save', ['class' => 'creat-btn', 'style' => 'padding: 17px 60px; margin-top: 20px; border: 0;']) ?></div>
                    </div>

                </div>
                <?php
                ActiveForm::end();
                ?>
            </div>
        </div>
    </div>


</div>

