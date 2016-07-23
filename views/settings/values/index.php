<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use \app\common\models\User;
use yii\widgets\ActiveForm;
use app\models\Categories;

$this->title = 'Values';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/js/values.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$baseUrl = Yii::$app->request->baseUrl . '/';
?>
<style>
    .help-block{
        font-size: 12px;
    }
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>

<!--<script src="<?php echo Yii::$app->request->baseUrl; ?>/js/user.js"></script>-->
<div class="row">
    <div class="col-md-2 leftbar ">
        <div class="sidebar content-box" style="display: block;">
            <div  class="back_btn">
                <a href="<?php echo Yii::$app->request->referrer; ?>"><img src="<?= $baseUrl ?>images/back.png" width="43" height="12" alt=""/></a>
            </div>
            <ul class="nav">
                <!-- Main menu -->
                <?php if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                    ?><li><a href="<?= Yii::$app->urlManager->createUrl("settings/plans/"); ?>">Plans</a></li> <?php } ?>
                <li><a href="<?= Yii::$app->urlManager->createUrl("user/my-account"); ?>">My Account</a></li>
                <?php if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                    ?><li><a href="<?= Yii::$app->urlManager->createUrl("user/"); ?>">Users</a></li> <?php } ?>
                <li class="select"><a href="<?= Yii::$app->urlManager->createUrl("settings/values/"); ?>">Values</a></li>
            </ul>
        </div>
    </div>
    <div class="col-md-10 content_wraper">
        <div class="content-box-large3" style="height: auto;min-height: 651px;">
            <div id="at" class="row">
                <p>Account Type</p>
            </div>	
            <div class="row accounttable">
                <p>Account Type</p>
                <div class="account-contents">
                    <div class="rec-row">
                        <!--<div class="clearfix">-->
                        <?php
                        if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                            $form = ActiveForm::begin(['action' => Yii::$app->urlManager->createUrl("settings/values/account-type"),
                                        'fieldConfig' => ['template' => "{input}{error}"],
                                        'validationUrl' => Yii::$app->urlManager->createUrl("user/create-validation"),
                                        'options' => ['id' => 'at-create-form', 'class' => 'clearfix acctype']]);
                            ?>
                            <div class="rec-btns">
                                <span>
                                    <a href="#"><img src="<?= $baseUrl ?>images/spacer.png" alt=""></a> 
                                    <!--<a href="#" class="margin_l15"><img src="<?= $baseUrl ?>images/save_icon2.png" alt=""></a>-->
                                    <?= Html::submitButton('', ['class' => 'margin_l15', 'style' => 'background: url(' . $baseUrl . 'images/save_icon2.png) no-repeat center center; width:42%; height:23px;border:0']) ?>
                                </span>
                            </div>
                            <!--<div class="rec-email"><span><input type="text" class="form-control" id="exampleInputName2" placeholder="AT"></span></div>-->
                            <?= $form->field($AccModel, 'type_name', ['options' => ['class' => 'rec-email'], 'inputOptions' => ['class' => 'form-control', 'placeholder' => "Type"]])->textInput() ?>
                            <?php
                            ActiveForm::end();
                        }
                        ?>
                        <!--</div>-->
                    </div>              
                    <!--<div class="rec-row">-->
                    <?php
                    if (count($accountData) > 0) {
                        foreach ($accountData as $d) {
                            ?>
                            <div class="rec-row">
                                <div id="<?= $d->_id . 'D' ?>" class="clearfix data" ondblclick="edit('<?= $d->_id ?>');">
                                    <div class="rec-btns">
                                        <span>
                                            <a href="javascript:;" onclick="edit('<?= $d->_id ?>');"><img src="<?= $baseUrl ?>images/edit-btn.png" alt=""></a> 
                                            <a href="javascript:;" class="margin_l15" onclick="removeAccType('<?= $d->_id ?>')"><img src="<?= $baseUrl ?>images/delete-btn.png" alt=""></a>
                                        </span>
                                    </div>
                                    <div class="rec-email"><span id="type_name"><?= $d->type_name ?></span></div>
                                </div>
                            </div>
                            <!-- for update AccType-->
                            <!--<div class="rec-row">-->
                            <!--<div class="clearfix">-->
                            <?php
                            if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                                $form = ActiveForm::begin([//'action' => Yii::$app->urlManager->createUrl("user/create"),
                                            'fieldConfig' => ['template' => "{input}{error}"],
                                            'validationUrl' => Yii::$app->urlManager->createUrl("user/update-validation"),
                                            //'enableAjaxValidation' => true,
                                            'options' => ['id' => $d->_id . 'E', 'class' => 'rec-row form-div hidden acctype']]);
                                $AccModelu->attributes = $d->attributes;
                                ?>
                                <div class="clearfix">
                                    <div class="rec-btns">
                                        <span>
                                            <a href="#"><img src="<?= $baseUrl ?>images/spacer.png" alt=""></a> 
                                            <?= Html::submitButton('', ['class' => 'margin_l15', 'style' => 'background: url(' . $baseUrl . 'images/save_icon2.png) no-repeat center center; width:42%; height:23px;border:0']) ?>
                                        </span>
                                    </div>
                                    <?= $form->field($AccModelu, '_id', ['options' => ['class' => 'rec-email hidden'], 'inputOptions' => ['class' => 'form-control', 'placeholder' => "Type"]])->textInput() ?>
                                    <?= $form->field($AccModelu, 'type_name', ['options' => ['class' => 'rec-email'], 'inputOptions' => ['class' => 'form-control', 'placeholder' => "Type"]])->textInput() ?>
                                </div>
                                <?php
                                ActiveForm::end();
                            }
                            ?>
                            <!--</div>-->
                            <!--</div>-->

                            <?php
                        }
                    }
                    ?>
                    <!--</div>-->             

                </div>
            </div> <!--'end account type -->
            <div class="clearfix"></div>	
            <br><br>
            <!-- Order Type :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
            <div id="ot" class="row">
                <p>Order Type</p>
            </div>	
            <div class="row accounttable">
                <p>Order Type</p>
                <div class="account-contents">
                    <div class="rec-row">
                        <!--<div class="clearfix">-->
                        <?php
                        if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                            $form = ActiveForm::begin(['action' => Yii::$app->urlManager->createUrl("settings/values/order-type"),
                                        'fieldConfig' => ['template' => "{input}{error}"],
                                        'validationUrl' => Yii::$app->urlManager->createUrl("user/create-validation"),
                                        'options' => ['id' => 'ot-create-form', 'class' => 'clearfix ordtype']]);
                            ?>
                            <div class="rec-btns">
                                <span>
                                    <a href="#"><img src="<?= $baseUrl ?>images/spacer.png" alt=""></a> 
                                    <!--<a href="#" class="margin_l15"><img src="<?= $baseUrl ?>images/save_icon2.png" alt=""></a>-->
                                    <?= Html::submitButton('', ['class' => 'margin_l15', 'style' => 'background: url(' . $baseUrl . 'images/save_icon2.png) no-repeat center center; width:42%; height:23px;border:0']) ?>
                                </span>
                            </div>
                            <!--<div class="rec-email"><span><input type="text" class="form-control" id="exampleInputName2" placeholder="AT"></span></div>-->
                            <?= $form->field($OrModel, 'type_name', ['options' => ['class' => 'rec-email'], 'inputOptions' => ['class' => 'form-control', 'placeholder' => "Type"]])->textInput() ?>
                            <?php
                            ActiveForm::end();
                        }
                        ?>
                        <!--</div>-->
                    </div>              
                    <!--<div class="rec-row">-->
                    <!-- Order type listing -->
                    <?php
                    if (count($orderData) > 0) {
                        foreach ($orderData as $d) {
                            ?>
                            <div class="rec-row">
                                <div id="<?= $d->_id . 'D' ?>" class="clearfix data" ondblclick="edit('<?= $d->_id ?>');">
                                    <div class="rec-btns">
                                        <span>
                                            <a href="javascript:;" onclick="edit('<?= $d->_id ?>');"><img src="<?= $baseUrl ?>images/edit-btn.png" alt=""></a> 
                                            <a href="javascript:;" class="margin_l15" onclick="removeOrdType('<?= $d->_id ?>')"><img src="<?= $baseUrl ?>images/delete-btn.png" alt=""></a>
                                        </span>
                                    </div>
                                    <div class="rec-email"><span id="type_name"><?= $d->type_name ?></span></div>
                                </div>
                            </div>
                            <!-- for update AccType-->
                            <!--<div class="rec-row">-->
                            <!--<div class="clearfix">-->
                            <?php
                            if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                                $form = ActiveForm::begin([//'action' => Yii::$app->urlManager->createUrl("user/create"),
                                            'fieldConfig' => ['template' => "{input}{error}"],
                                            //'validationUrl' => Yii::$app->urlManager->createUrl("user/update-validation"),
                                            //'enableAjaxValidation' => true,
                                            'options' => ['id' => $d->_id . 'E', 'class' => 'rec-row form-div hidden ordtype']]);
                                $OrModelu->attributes = $d->attributes;
                                ?>
                                <div class="clearfix">
                                    <div class="rec-btns">
                                        <span>
                                            <a href="#"><img src="<?= $baseUrl ?>images/spacer.png" alt=""></a> 
                                            <?= Html::submitButton('', ['class' => 'margin_l15', 'style' => 'background: url(' . $baseUrl . 'images/save_icon2.png) no-repeat center center; width:42%; height:23px;border:0']) ?>
                                        </span>
                                    </div>
                                    <?= $form->field($OrModelu, '_id', ['options' => ['class' => 'rec-email hidden'], 'inputOptions' => ['class' => 'form-control']])->textInput() ?>
                                    <?= $form->field($OrModelu, 'type_name', ['options' => ['class' => 'rec-email'], 'inputOptions' => ['class' => 'form-control', 'placeholder' => "Type"]])->textInput() ?>
                                </div>
                                <?php
                                ActiveForm::end();
                            }
                            ?>
                            <!--</div>-->
                            <!--</div>-->

                            <?php
                        }
                    }
                    ?>
                    <!--</div>-->          

                </div>
            </div> <!--'end Order type -->
            <div class="clearfix"></div>	
            <br><br>

            <!-- Sale Estimation :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
            <div id="est" class="row">
                <p>Sale Estimation</p>
            </div>	
            <div class="row accounttable">
                <p>Sale Estimation</p>
                <div class="account-contents">
                    <!--..,,,,,,,,,,,,,,,,,,,,,,,,,,, Verified -->
                    <div class="rec-row">
                        <?php
                        foreach ($VData as $key => $value) {
                            $VData[$value->name] = $VData[$key];
                            unset($VData[$key]);
                        }
                        if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                            $form = ActiveForm::begin(['action' => Yii::$app->urlManager->createUrl("settings/values/value"),
                                        'fieldConfig' => ['template' => "{input}{error}"],
                                        //'validationUrl' => Yii::$app->urlManager->createUrl("user/create-validation"),
                                        'options' => ['id' => 'F-form', 'class' => 'clearfix ordtype']]);
                            $valuesModel->value = (isset($VData['Verified']->value)) ? $VData['Verified']->value : '';
                            ?>
                            <div class="rec-btns">
                                <span>
                                    <a href="#"><img src="<?= $baseUrl ?>images/spacer.png" alt=""></a> 
                                    <!--<a href="#" class="margin_l15"><img src="<?= $baseUrl ?>images/save_icon2.png" alt=""></a>-->
                                    <?= Html::submitButton('', ['class' => 'margin_l15', 'style' => 'background: url(' . $baseUrl . 'images/save_icon2.png) no-repeat center center; width:42%; height:23px;border:0']) ?>
                                </span>
                            </div>
                            <?= $form->field($valuesModel, 'name', ['options' => ['class' => 'rec-email hidden', 'style' => 'margin-left: 5%;width:25%'], 'inputOptions' => ['class' => 'form-control', 'placeholder' => "Days", 'value' => 'Verified']])->textInput() ?>
                            <div class="rec-email" style="width:21%"><span id="type_name">Verified</span></div>
                            <?= $form->field($valuesModel, 'value', ['options' => ['class' => 'rec-email', 'style' => 'margin-left: 5%;width:25%'], 'inputOptions' => ['class' => 'form-control', 'placeholder' => "Days", 'type' => "number", 'min' => "0"]])->textInput() ?>
                            <?php
                            ActiveForm::end();
                        }
                        ?>
                    </div>
                    <!--..,,,,,,,,,,,,,,,,,,,,,,,,,,,Submitted to FIN -->
                    <div class="rec-row">
                        <?php
                        if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                            $form = ActiveForm::begin(['action' => Yii::$app->urlManager->createUrl("settings/values/value"),
                                        'fieldConfig' => ['template' => "{input}{error}"],
                                        //'validationUrl' => Yii::$app->urlManager->createUrl("user/create-validation"),
                                        'options' => ['id' => 'AT-form', 'class' => 'clearfix ordtype']]);
                            $valuesModel->value = (isset($VData['Submitted to FIN']->value)) ? $VData['Submitted to FIN']->value : '';
                            ?>
                            <div class="rec-btns">
                                <span>
                                    <a href="#"><img src="<?= $baseUrl ?>images/spacer.png" alt=""></a> 
                                    <!--<a href="#" class="margin_l15"><img src="<?= $baseUrl ?>images/save_icon2.png" alt=""></a>-->
                                    <?= Html::submitButton('', ['class' => 'margin_l15', 'style' => 'background: url(' . $baseUrl . 'images/save_icon2.png) no-repeat center center; width:42%; height:23px;border:0']) ?>
                                </span>
                            </div>
                            <?= $form->field($valuesModel, 'name', ['options' => ['class' => 'rec-email hidden', 'style' => 'margin-left: 5%;width:25%'], 'inputOptions' => ['class' => 'form-control', 'placeholder' => "Days", 'value' => 'Submitted to FIN']])->textInput() ?>
                            <div class="rec-email" style="width:21%"><span id="type_name">Submitted to FIN</span></div>
                            <?= $form->field($valuesModel, 'value', ['options' => ['class' => 'rec-email', 'style' => 'margin-left: 5%;width:25%'], 'inputOptions' => ['class' => 'form-control', 'placeholder' => "Days", 'type' => "number", 'min' => "0"]])->textInput() ?>
                            <?php
                            ActiveForm::end();
                        }
                        ?>
                    </div>
                    <!--..,,,,,,,,,,,,,,,,,,,,,,,,,,, FIN Approved -->
                    <div class="rec-row">
                        <?php
                        if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                            $form = ActiveForm::begin(['action' => Yii::$app->urlManager->createUrl("settings/values/value"),
                                        'fieldConfig' => ['template' => "{input}{error}"],
                                        //'validationUrl' => Yii::$app->urlManager->createUrl("user/create-validation"),
                                        'options' => ['id' => 'LD-form', 'class' => 'clearfix ordtype']]);
                            $valuesModel->value = (isset($VData['FIN Approved']->value)) ? $VData['FIN Approved']->value : '';
                            ?>
                            <div class="rec-btns">
                                <span>
                                    <a href="#"><img src="<?= $baseUrl ?>images/spacer.png" alt=""></a> 
                                    <!--<a href="#" class="margin_l15"><img src="<?= $baseUrl ?>images/save_icon2.png" alt=""></a>-->
                                    <?= Html::submitButton('', ['class' => 'margin_l15', 'style' => 'background: url(' . $baseUrl . 'images/save_icon2.png) no-repeat center center; width:42%; height:23px;border:0']) ?>
                                </span>
                            </div>
                            <?= $form->field($valuesModel, 'name', ['options' => ['class' => 'rec-email hidden', 'style' => 'margin-left: 5%;width:25%'], 'inputOptions' => ['class' => 'form-control', 'placeholder' => "Days", 'value' => 'FIN Approved']])->textInput() ?>
                            <div class="rec-email" style="width:21%"><span id="type_name">FIN Approved</span></div>
                            <?= $form->field($valuesModel, 'value', ['options' => ['class' => 'rec-email', 'style' => 'margin-left: 5%;width:25%'], 'inputOptions' => ['class' => 'form-control', 'placeholder' => "Days", 'type' => "number", 'min' => "0"]])->textInput() ?>
                            <?php
                            ActiveForm::end();
                        }
                        ?>
                    </div>
                    <!--..,,,,,,,,,,,,,,,,,,,,,,,,,,,Submitted to AT-->
                    <div class="rec-row">
                        <?php
                        if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                            $form = ActiveForm::begin(['action' => Yii::$app->urlManager->createUrl("settings/values/value"),
                                        'fieldConfig' => ['template' => "{input}{error}"],
                                        //'validationUrl' => Yii::$app->urlManager->createUrl("user/create-validation"),
                                        'options' => ['id' => 'RG-form', 'class' => 'clearfix ordtype']]);
                            $valuesModel->value = (isset($VData['Submitted to AT']->value)) ? $VData['Submitted to AT']->value : '';
                            ?>
                            <div class="rec-btns">
                                <span>
                                    <a href="#"><img src="<?= $baseUrl ?>images/spacer.png" alt=""></a> 
                                    <!--<a href="#" class="margin_l15"><img src="<?= $baseUrl ?>images/save_icon2.png" alt=""></a>-->
                                    <?= Html::submitButton('', ['class' => 'margin_l15', 'style' => 'background: url(' . $baseUrl . 'images/save_icon2.png) no-repeat center center; width:42%; height:23px;border:0']) ?>
                                </span>
                            </div>
                            <?= $form->field($valuesModel, 'name', ['options' => ['class' => 'rec-email hidden', 'style' => 'margin-left: 5%;width:25%'], 'inputOptions' => ['class' => 'form-control', 'placeholder' => "Days", 'value' => 'Submitted to AT']])->textInput() ?>
                            <div class="rec-email" style="width:21%"><span id="type_name">Submitted to AT</span></div>
                            <?= $form->field($valuesModel, 'value', ['options' => ['class' => 'rec-email', 'style' => 'width:25%;margin-left: 5%;'], 'inputOptions' => ['class' => 'form-control', 'placeholder' => "Days", 'type' => "number", 'min' => "0"]])->textInput() ?>
                            <?php
                            ActiveForm::end();
                        }
                        ?>
                    </div>
                    <!--..,,,,,,,,,,,,,,,,,,,,,,,,,,,AT Approved-->
                    <div class="rec-row">
                        <?php
                        if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                            $form = ActiveForm::begin(['action' => Yii::$app->urlManager->createUrl("settings/values/value"),
                                        'fieldConfig' => ['template' => "{input}{error}"],
                                        //'validationUrl' => Yii::$app->urlManager->createUrl("user/create-validation"),
                                        'options' => ['id' => 'AT-Approved-form', 'class' => 'clearfix ordtype']]);
                            $valuesModel->value = (isset($VData['AT Approved']->value)) ? $VData['AT Approved']->value : '';
                            ?>
                            <div class="rec-btns">
                                <span>
                                    <a href="#"><img src="<?= $baseUrl ?>images/spacer.png" alt=""></a> 
                                    <!--<a href="#" class="margin_l15"><img src="<?= $baseUrl ?>images/save_icon2.png" alt=""></a>-->
                                    <?= Html::submitButton('', ['class' => 'margin_l15', 'style' => 'background: url(' . $baseUrl . 'images/save_icon2.png) no-repeat center center; width:42%; height:23px;border:0']) ?>
                                </span>
                            </div>
                            <?= $form->field($valuesModel, 'name', ['options' => ['class' => 'rec-email hidden', 'style' => 'margin-left: 5%;width:25%'], 'inputOptions' => ['class' => 'form-control', 'placeholder' => "Days", 'value' => 'AT Approved']])->textInput() ?>
                            <div class="rec-email" style="width:21%"><span id="type_name">AT Approved</span></div>
                            <?= $form->field($valuesModel, 'value', ['options' => ['class' => 'rec-email', 'style' => 'width:25%;margin-left: 5%;'], 'inputOptions' => ['class' => 'form-control', 'placeholder' => "Days", 'type' => "number", 'min' => "0"]])->textInput() ?>
                            <?php
                            ActiveForm::end();
                        }
                        ?>
                    </div>
                    <!--..,,,,,,,,,,,,,,,,,,,,,,,,,,,SO Assigned-->
                    <div class="rec-row">
                        <?php
                        if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                            $form = ActiveForm::begin(['action' => Yii::$app->urlManager->createUrl("settings/values/value"),
                                        'fieldConfig' => ['template' => "{input}{error}"],
                                        //'validationUrl' => Yii::$app->urlManager->createUrl("user/create-validation"),
                                        'options' => ['id' => 'SO-form', 'class' => 'clearfix ordtype']]);
                            $valuesModel->value = (isset($VData['SO Assigned']->value)) ? $VData['SO Assigned']->value : '';
                            ?>
                            <div class="rec-btns">
                                <span>
                                    <a href="#"><img src="<?= $baseUrl ?>images/spacer.png" alt=""></a> 
                                    <!--<a href="#" class="margin_l15"><img src="<?= $baseUrl ?>images/save_icon2.png" alt=""></a>-->
                                    <?= Html::submitButton('', ['class' => 'margin_l15', 'style' => 'background: url(' . $baseUrl . 'images/save_icon2.png) no-repeat center center; width:42%; height:23px;border:0']) ?>
                                </span>
                            </div>
                            <?= $form->field($valuesModel, 'name', ['options' => ['class' => 'rec-email hidden', 'style' => 'margin-left: 5%;width:25%'], 'inputOptions' => ['class' => 'form-control', 'placeholder' => "Days", 'value' => 'SO Assigned']])->textInput() ?>
                            <div class="rec-email" style="width:21%"><span id="type_name">SO Assigned</span></div>
                            <?= $form->field($valuesModel, 'value', ['options' => ['class' => 'rec-email', 'style' => 'width:25%;margin-left: 5%;'], 'inputOptions' => ['class' => 'form-control', 'placeholder' => "Days", 'type' => "number", 'min' => "0"]])->textInput() ?>
                            <?php
                            ActiveForm::end();
                        }
                        ?>
                    </div>
                    <!--..,,,,,,,,,,,,,,,,,,,,,,,,,,, ARC -->
                    <div class="rec-row">
                        <?php
                        if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                            $form = ActiveForm::begin(['action' => Yii::$app->urlManager->createUrl("settings/values/value"),
                                        'fieldConfig' => ['template' => "{input}{error}"],
                                        //'validationUrl' => Yii::$app->urlManager->createUrl("user/create-validation"),
                                        'options' => ['id' => 'ARC-form', 'class' => 'clearfix ordtype']]);
                            $valuesModel->value = (isset($VData['ARC']->value)) ? $VData['ARC']->value : '';
                            ?>
                            <div class="rec-btns">
                                <span>
                                    <a href="#"><img src="<?= $baseUrl ?>images/spacer.png" alt=""></a> 
                                    <!--<a href="#" class="margin_l15"><img src="<?= $baseUrl ?>images/save_icon2.png" alt=""></a>-->
                                    <?= Html::submitButton('', ['class' => 'margin_l15', 'style' => 'background: url(' . $baseUrl . 'images/save_icon2.png) no-repeat center center; width:42%; height:23px;border:0']) ?>
                                </span>
                            </div>
                            <?= $form->field($valuesModel, 'name', ['options' => ['class' => 'rec-email hidden', 'style' => 'margin-left: 5%;width:25%'], 'inputOptions' => ['class' => 'form-control', 'placeholder' => "Days", 'value' => 'ARC']])->textInput() ?>
                            <div class="rec-email" style="width:21%"><span id="type_name">Activated/Rejected/Cancelled</span></div>
                            <?= $form->field($valuesModel, 'value', ['options' => ['class' => 'rec-email', 'style' => 'width:25%;margin-left: 5%;'], 'inputOptions' => ['class' => 'form-control', 'placeholder' => "Days", 'type' => "number", 'min' => "0"]])->textInput() ?>
                            <?php
                            ActiveForm::end();
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!--            <div class="row">
                            <h1 style="text-align: center">No records found</h1>
                        </div>-->

        </div>

    </div>

</div>

