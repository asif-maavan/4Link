<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use \app\common\models\User;
use yii\widgets\ActiveForm;
use app\models\Categories;

$this->title = 'Sales Detail';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/js/sales.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$baseUrl = Yii::$app->request->baseUrl . '/';

$sdate = ($model->order_state == 'Created') ? date('Y-m-d H:i:s', $model->created->sec) : $model->date_of_order_state;
//$since = (int) ((time() - strtotime($sdate)) / (60 * 60 * 24));
$since = subDiff(date('d/m/Y', strtotime($sdate)), date('d/m/Y'), TRUE);
$since = $since->format("%a");

function subDiff($d1, $d2, $date = FALSE) {
    $d1 = str_replace('/', '-', $d1);
    $d2 = str_replace('/', '-', $d2);
    $date1 = new DateTime($d1);
    $date2 = new DateTime($d2);
    $diff = date_diff($date1, $date2);
    if ($date) {
        return $diff;
    }
    return $diff->format("%R%a");
//    return $d1;
}
?>
<script>
    var newCustomer = "<?= ($model->customer_type == '1') ? $model->customer_name['_id'] : '' ?>";
</script>
<style>
    .form-control {
        margin: 0px !important;
    }
    textarea{
        height: 135px !important;
    }
    .datepicker{
        background: #FFF url(../images/calendar.jpg) no-repeat right center;background-position: 96% 50% !important;
    }
</style>
<div class="page-content">
    <div class="row">
        <div class="col-md-12 sales-detail-upper-wraper">
            <?php
            $form = ActiveForm::begin([//'action' => Yii::$app->urlManager->createUrl("user/create"),
                        'fieldConfig' => ['template' => "{input}{error}"],
                        'validationUrl' => Yii::$app->urlManager->createUrl("sales/validation?s=detail"),
                        //'enableAjaxValidation' => true,
                        'options' => ['id' => 'detailForm', 'class' => '']]);
            ?>
            <?= $form->field($model, '_id', ['options' => ['class' => 'divTableCell hidden'], 'inputOptions' => ['class' => 'form-control hidden']])->textInput() ?>

            <div class="row">
                <div class="col-md-3">
                    <div class="back_btn2">
                        <a href="<?= Yii::$app->urlManager->createUrl("sales/"); ?>"><img src="<?= $baseUrl ?>images/back.png" width="43" height="12" alt=""></a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="sales-detail-upper-title text-center">Order State: <?= $model->order_state ?> since <?= ($since == 0) ? 'Today' : (($since == 1) ? ' Yesterday' : $since . ' Days') ?> </div>
                </div>
                <div class="col-md-3">
                    <div>
                        <a href="#" class="save-btn-2" onclick="$('#saveBtn').click();">Save</a>
                    </div>
                </div>
            </div>
            <div class="row" style="background-color:#fff;">
                <div class="col-lg-12 ui-d">UID: <strong>S<?= $model->uid ?></strong></div>
            </div>  
            <div class="row" style="background-color:#fff; margin-top:10px;">
                <div class="col-xs-12">
                    <div class="camp-info3"><span></span>Summary</div>
                </div>
                <div class="container col-lg-10" style="margin: 0px 0px 0px 100px;">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Order State:</div>
                                <?= $form->field($model, 'order_state', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style ost', 'style' => '', 'onChange' => '$(".ost").val($(this).val())']])->dropDownList($orderStateList, ['prompt' => 'Select']) ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Index#:</div>
                                <div class="col-xs-6"><input type="text" disabled class="form-control input_style input_dis" value="<?= $model->index_no ?>" id="usr"></div>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Date Submitted:</div>
                                <?= $form->field($model, 'submitted', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style datepicker', 'style' => 'background: #FFF url(../images/calendar.jpg) no-repeat right center !important;background-position: 96% 50% !important;']])->textInput() ?>
                            </div>                        
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Sales Agent:</div>
                                <?= $form->field($model, 'sale_executive', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style', 'style' => '', 'onChange' => 'getExecutive(this)']])->dropDownList($agentList, ['prompt' => 'Select']) ?>
                            </div>                        
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Team Leader:</div>
                                <div class="col-xs-6"><input id="team_leader" type="text" disabled class="form-control input_style input_dis" value="<?= ($model->team_leader) ? $model->team_leader['name'] : '-' ?>" id="usr"></div>
                            </div>                        

                        </div>
                        <div class="col-xs-6">
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Order Type:</div>
                                <?= $form->field($model, 'order_type', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style', 'style' => '']])->dropDownList($orderTypeList, ['prompt' => 'Select']) ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Customer Name:</div>
<!--                                <div class="col-xs-6" style="position:relative;"><input type="text" class="form-control input_style" id="usr">
                                </div>-->
                                <!--<a href="#"><img src="<?= $baseUrl ?>images/addnew-btn.png" alt="" style="position:absolute;right: -17px;"/></a>-->
                                <?= $form->field($model, 'customer_name', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style', 'style' => '', 'onChange' => 'getC(this)']])->dropDownList($customerList, ['prompt' => 'Select']) ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Customer Type:</div>
                                <!--<div class="col-xs-6"><input id="customer_type" type="text" disabled class="form-control input_style input_dis" value="<?= $customerTypeList[$model->customer_type] ?>" id="usr"></div>-->
                                <?= $form->field($model, 'customer_type', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['id' => 'customer_type', 'class' => 'form-control input_style input_dis', 'style' => 'cursor: default;', 'disabled' => '', 'value' => $customerTypeList[$model->customer_type]]])->textInput() ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Account Type:</div>
                                <div class="col-xs-6"><input id="account_type" type="text" disabled class="form-control input_style input_dis" value="-" id="usr"></div>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Customer Acc#:</div>
                                <!--<div class="col-xs-6"><input id="salesform-customer_acc_no" type="text" disabled class="form-control input_style input_dis" value="<?= $model->customer_acc_no ?>" id="usr"></div>-->
                                <?= $form->field($model, 'customer_acc_no', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style input_dis', 'style' => '', 'readonly' => '']])->textInput() ?>
                            </div>
                        </div>                    
                    </div>
                </div>
            </div>

            <!--.............................................................. Plan  -->
            <div class="row" style="background-color:#fff; margin-top:10px;">
                <div class="col-xs-12">
                    <div class="camp-info3"><span></span>Plan</div>
                </div>
                <div class="container col-lg-10" style="margin: 0px 0px 0px 100px;">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Plan:</div>
                                <!--                                <div class="col-xs-6">
                                                                    <span class="arrow2"></span> 
                                                                </div>-->
                                <?= $form->field($model, 'plan', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style', 'style' => '', 'onchange' => 'getPlan()']])->dropDownList($planList, ['prompt' => 'Select']) ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Plan Group:</div>
                                <div class="col-xs-6"><input id="plan_group" type="text" disabled="" class="form-control input_style input_dis" value="<?= !empty($model->plan_group) ? $model->plan_group : '-' ?>" id="usr"></div>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Plan Type:</div>
                                <div class="col-xs-6"><input id="plan_type" type="text" disabled="" class="form-control input_style input_dis" value="<?= !empty($model->plan_type) ? $planTypeList[$model->plan_type] : '-' ?>" id="usr"></div>

                            </div>                        
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Qty:</div>
                                <?= $form->field($model, 'QTY', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style', 'style' => '']])->textInput() ?>
                            </div>                        
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">MRC:</div>
                                <div class="col-xs-6"><input id="mrc" type="text" disabled="" class="form-control input_style input_dis" value="<?= !empty($model->MRC) ? $model->MRC : '-' ?>" id="usr"></div>

                            </div>                        

                        </div>
                        <div class="col-xs-6">
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Contract Period:</div>
                                <div class="col-xs-6"><input id="contract_period" type="text" disabled="" class="form-control input_style input_dis" value="<?= !empty($model->contract_period) ? $model->contract_period : '-' ?>" id="usr"></div>

                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Contract Renewal Date:</div>
                                <div class="col-xs-6"><input id="contract_renewal_date" type="text" disabled="" class="form-control input_style input_dis" value="<?= (getRenewalDate($model) != '-') ? getRenewalDate($model)->format('M d, Y') : '-'; ?>" id="usr"></div>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">PipeLine:</div>
                                <?= $form->field($model, 'pipe_line', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style', 'style' => '']])->dropDownList([], ['prompt' => 'Select']) ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Siebel Activity #:</div>
                                <?= $form->field($model, 'siebel_activity_no', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style', 'style' => '']])->textInput() ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Four Link Points:</div>
                                <div class="col-xs-6"><input id="fourlink_points" type="text" disabled="" class="form-control input_style input_dis" value="<?= !empty($model->four_link_points) ? $model->four_link_points : '-' ?>" id="usr"></div>
                            </div>
                        </div>                    
                    </div>
                </div>
            </div>

            <!--............................................................ Finance  -->
            <?php
            $fDisable = ($model->require_finance == '1') ? FALSE : true;
            $_fDisable = $fDisable ? 'input_dis' : '';
            ?>
            <div class="row" style="background-color:#fff; margin-top:10px;">
                <div class="col-xs-12">
                    <div class="camp-info3"><span></span>Finance</div>
                </div>
                <div class="container col-lg-10" style="margin: 0px 0px 0px 100px;">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">F. Indicator:</div>
                                <?= $form->field($model, 'f_indicator', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style ' . $_fDisable, 'style' => '', 'disabled' => $fDisable]])->textInput() ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Require Finance:</div>
                                <?= $form->field($model, 'require_finance', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style', 'style' => '', 'onChange' => 'toggleFIN(this)']])->dropDownList($YN, ['prompt' => 'Select']) ?>
                                
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Order Sub & F. Sub Difference:</div>
                                <div class="col-xs-6"><input id="f_difference" type="text" disabled="" class="form-control input_style input_dis" value="<?= (!empty($model->submitted) && !empty($model->submitted_to_finance)) ? subDiff($model->submitted, $model->submitted_to_finance) : '-' ?>" id="usr"></div>
                            </div>                        
                        </div>
                        <div class="col-xs-6">
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Submitted to Finance:</div>
                                <!--<div class="col-xs-6"><input type="text" class="form-control input_style" id="usr"></div>-->
                                <?= $form->field($model, 'submitted_to_finance', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style datepicker ' . $_fDisable, 'style' => '', 'disabled' => $fDisable]])->textInput() ?>
                                <input type="button" value="submitted_to_finance">
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">F. Response:</div>
                                <!--<div class="col-xs-6"><input type="text" class="form-control input_style" id="usr"></div>-->
                                <?= $form->field($model, 'f_response', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style ' . $_fDisable, 'style' => '', 'disabled' => $fDisable]])->textInput() ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">State:</div>
                                <!--                                <div class="col-xs-6">
                                                                    <span class="arrow2"></span>
                                                                    <select class="form-control input_style" id="sel2">
                                                                        <option></option>
                                                                    </select>
                                                                </div>-->
                                <?= $form->field($model, 'f_state', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style ' . $_fDisable, 'style' => '', 'disabled' => $fDisable]])->dropDownList($states, ['prompt' => 'Select']) ?>
                            </div>

                        </div> 
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row custrow" style="padding-left: 15px;"> 
                                    <div class="col-xs-2 custlabel_2">Comments :</div>
                                    <?= $form->field($model, 'f_comments', ['options' => ['class' => 'col-xs-10'], 'inputOptions' => ['class' => 'form-control textarea_style', 'style' => '']])->textarea() ?>
                                </div>                        

                            </div>
                        </div>                   
                    </div>
                </div>
            </div>

            <!--.............................................................. Account Transfer  -->
            <?php
            $ATDisable = ($model->require_account_transfer == '1') ? FALSE : true;
            $fATDisable = $ATDisable ? 'input_dis' : '';
            ?>
            <div class="row" style="background-color:#fff; margin-top:10px;">
                <div class="col-xs-12">
                    <div class="camp-info3"><span></span>Account Transfer </div>
                </div>
                <div class="container col-lg-10" style="margin: 0px 0px 0px 100px;">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">A.T. Indicator:</div>
                                <!--<div class="col-xs-6"><input type="text" class="form-control input_style" id="usr"></div>-->
                                <?= $form->field($model, 'AT_indicator', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style ' . $fATDisable, 'style' => '', 'disabled' => $ATDisable]])->textInput() ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Require A.T.:</div>
                                <?= $form->field($model, 'require_account_transfer', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style', 'style' => '', 'onChange' => 'toggleAT(this)']])->dropDownList($YN, ['prompt' => 'Select']) ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Order Sub & A.T. Sub Difference:</div>
                                <div class="col-xs-6"><input id="at_difference" type="text" disabled="" class="form-control input_style input_dis" value="<?= (!empty($model->submitted) && !empty($model->submitted_to_AT)) ? subDiff($model->submitted, $model->submitted_to_AT) : '-' ?>" id="usr"></div>

                            </div>                        
                        </div>
                        <div class="col-xs-6">
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Submitted to A.T.:</div>
                                <!--<div class="col-xs-6"><input type="text" class="form-control input_style" id="usr"></div>-->
                                <?= $form->field($model, 'submitted_to_AT', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style datepicker ' . $fATDisable, 'style' => '', 'disabled' => $ATDisable]])->textInput() ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">A.T. Response:</div>
                                <!--<div class="col-xs-6"><input type="text" class="form-control input_style" id="usr"></div>-->
                                <?= $form->field($model, 'AT_response', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style ' . $fATDisable, 'style' => '', 'disabled' => $ATDisable]])->textInput() ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">State:</div>
                                <!--                                <div class="col-xs-6">
                                                                    <span class="arrow2"></span>
                                                                    <select class="form-control input_style" id="sel2">
                                                                        <option></option>
                                                                    </select>
                                                                </div>-->
                                <?= $form->field($model, 'AT_state', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style ' . $fATDisable, 'style' => '', 'disabled' => $ATDisable]])->dropDownList($states, ['prompt' => 'Select']) ?>
                            </div>

                        </div> 
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row custrow" style="padding-left: 15px;"> 
                                    <div class="col-xs-2 custlabel_2">Comments :</div>
                                    <!--<div class="col-xs-10"><input type="text" class="form-control textarea_style" id="usr"></div>-->
                                    <?= $form->field($model, 'AT_comments', ['options' => ['class' => 'col-xs-10'], 'inputOptions' => ['class' => 'form-control textarea_style', 'style' => '']])->textarea() ?>
                                </div>                        

                            </div>
                        </div>                   
                    </div>
                </div>
            </div>

            <!--.............................................................. Logistics/Delivery  -->
            <?php
            $LDDisable = ($model->require_logistic_dep == '1') ? FALSE : true;
            $fLDDisable = $LDDisable ? 'input_dis' : '';
            ?>
            <div class="row" style="background-color:#fff; margin-top:10px;">
                <div class="col-xs-12">
                    <div class="camp-info3"><span></span>Logistics/Delivery</div>
                </div>
                <div class="container col-lg-10" style="margin: 0px 0px 0px 100px;">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">L.D. Indicator:</div>
                                <!--<div class="col-xs-6"><input type="text" class="form-control input_style" id="usr"></div>-->
                                <?= $form->field($model, 'LD_indicator', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style ' . $fLDDisable, 'style' => '', 'disabled' => $LDDisable]])->textInput() ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Require Logistic Dep:</div>
                                <?= $form->field($model, 'require_logistic_dep', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style', 'style' => '', 'onChange' => 'toggleLD(this)']])->dropDownList($YN, ['prompt' => 'Select']) ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Order Sub &amp; L.D. Sub Difference:</div>
                                <div class="col-xs-6"><input id="LD_difference" type="text" disabled="" class="form-control input_style input_dis" value="<?= (!empty($model->submitted) && !empty($model->submitted_to_LD)) ? subDiff($model->submitted, $model->submitted_to_LD) : '-' ?>" id="usr"></div>
                            </div>   
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2"> State:</div>
                                <!--                                <div class="col-xs-6">
                                                                    <span class="arrow2"></span>
                                                                    <select class="form-control input_style" id="sel2">
                                                                        <option></option>
                                                                    </select>
                                                                </div>-->
                                <?= $form->field($model, 'LD_state', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style ' . $fLDDisable, 'style' => '', 'disabled' => $LDDisable]])->dropDownList($states, ['prompt' => 'Select']) ?>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Sale Number:</div>
<!--                                <div class="col-xs-6"><input type="text" class="form-control input_style" id="usr"></div>-->
                                <?= $form->field($model, 'so_no', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style ', 'style' => ''], 'enableAjaxValidation' => true])->textInput() ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Submitted to L. D.:</div>
                                <!--<div class="col-xs-6"><input type="text" class="form-control input_style" id="usr"></div>-->
                                <?= $form->field($model, 'submitted_to_LD', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style datepicker ' . $fLDDisable, 'style' => '', 'disabled' => $LDDisable]])->textInput() ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">L.D. Response:</div>
                                <!--<div class="col-xs-6"><input type="text" class="form-control input_style" id="usr"></div>-->
                                <?= $form->field($model, 'LD_response', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style ' . $fLDDisable, 'style' => '', 'disabled' => $LDDisable]])->textInput() ?>
                            </div>

                        </div> 
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row custrow" style="padding-left: 15px;"> 
                                    <div class="col-xs-2 custlabel_2">Comments                                                     :</div>
                                    <!--<div class="col-xs-10"><input type="text" class="form-control textarea_style" id="usr"></div>-->
                                    <?= $form->field($model, 'LD_comments', ['options' => ['class' => 'col-xs-10'], 'inputOptions' => ['class' => 'form-control textarea_style', 'style' => '']])->textarea() ?>
                                </div>                        

                            </div>
                        </div>                   
                    </div>
                </div>
            </div>

            <!--.............................................................. Resolver Group  -->
            <?php
            $RGDisable = ($model->require_resolver_group == '1') ? FALSE : true;
            $fRGDisable = $RGDisable ? 'input_dis' : '';
            ?>
            <div class="row" style="background-color:#fff; margin-top:10px;">
                <div class="col-xs-12">
                    <div class="camp-info3"><span></span>Resolver Group</div>
                </div>
                <div class="container col-lg-10" style="margin: 0px 0px 0px 100px;">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">R.G. Indicator:</div>
                                <?= $form->field($model, 'RG_indicator', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style ' . $fRGDisable, 'style' => '', 'disabled' => $RGDisable]])->textInput() ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Require R. G.:</div>
                                <?= $form->field($model, 'require_resolver_group', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style', 'style' => '', 'onChange' => 'toggleRG(this)']])->dropDownList($YN, ['prompt' => 'Select']) ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Order Sub &amp; R.G. Sub Difference:</div>
                                <div class="col-xs-6"><input id="RG_difference" type="text" disabled="" class="form-control input_style input_dis" value="<?= (!empty($model->submitted) && !empty($model->submitted_to_RG)) ? subDiff($model->submitted, $model->submitted_to_RG) : '-' ?>" id="usr"></div>
                            </div>                        
                        </div>
                        <div class="col-xs-6">
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Submitted to R. G.:</div>
                                <?= $form->field($model, 'submitted_to_RG', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style datepicker ' . $fRGDisable, 'style' => '', 'disabled' => $RGDisable]])->textInput() ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">R.G. Response:</div>
                                <?= $form->field($model, 'RG_response', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style ' . $fRGDisable, 'style' => '', 'disabled' => $RGDisable]])->textInput() ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">State:</div>
                                <!--                                <div class="col-xs-6">
                                                                    <span class="arrow2"></span>
                                                                    <select class="form-control input_style" id="sel2">
                                                                        <option></option>
                                                                    </select>
                                                                </div>-->
                                <?= $form->field($model, 'RG_state', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style ' . $fRGDisable, 'style' => '', 'disabled' => $RGDisable]])->dropDownList($states, ['prompt' => 'Select']) ?>
                            </div>

                        </div> 
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row custrow" style="padding-left: 15px;"> 
                                    <div class="col-xs-2 custlabel_2">Comments                                                     :</div>
                                    <?= $form->field($model, 'RG_comments', ['options' => ['class' => 'col-xs-10'], 'inputOptions' => ['class' => 'form-control textarea_style', 'style' => '']])->textarea() ?>
                                </div>                        

                            </div>
                        </div>                   
                    </div>
                </div>
            </div>

            <!--.............................................................. Order State  -->
            <div class="row" style="background-color:#fff; margin-top:10px;">
                <div class="col-xs-12">
                    <div class="camp-info3"><span></span>Order State</div>
                </div>
                <div class="container col-lg-10" style="margin: 0px 0px 0px 100px;">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Order State:</div>
                                <!--                                <div class="col-xs-6">
                                                                    <span class="arrow2"></span>
                                                                    <select class="form-control input_style" id="sel2">
                                                                        <option>Created</option>
                                                                    </select>
                                                                </div>-->
                                <?= $form->field($model, 'order_state', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style ost', 'style' => '', 'onChange' => '$(".ost").val($(this).val())']])->dropDownList($orderStateList, ['prompt' => 'Select']) ?>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Date of Order State:</div>
                                <div class="col-xs-6"><input type="text" disabled="" class="form-control input_style input_dis" value="<?= $model->date_of_order_state ? date('M d, Y', strtotime($model->date_of_order_state)) : '-' ?>" id="usr"></div>

                            </div>

                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Est. Activation Date:</div>
                                <div class="col-xs-6"><input type="text" disabled="" class="form-control input_style input_dis" value="<?= getEstDate($model, $estList) != '-' ? getEstDate($model, $estList)->format('M d, Y') : '-' ?>" id="usr"></div>

                            </div><div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Est. &amp; Sub. Diff.:</div>
                                <div class="col-xs-6"><input type="text" disabled="" class="form-control input_style input_dis" value="<?= (isset($model->submitted) && getEstDate($model, $estList) != '-') ? subDiff($model->submitted, getEstDate($model, $estList)->format('d/m/Y')) : '-' ?>" id="usr"></div>

                            </div>                        
                        </div>
                        <div class="col-xs-6">
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Est. & Actual Difference:</div>
                                <div class="col-xs-6"><input type="text" name="SalesForm[est_actual_difference]" readonly="" class="form-control input_style input_dis" value="<?= ($model->date_of_order_state && getEstDate($model, $estList) != '-') ? subDiff(getEstDate($model, $estList)->format('d/m/Y'), date('d/m/Y', strtotime($model->date_of_order_state))) : '-' ?>" id="usr"></div>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Total M.R.C. Per Order:</div>
                                <div class="col-xs-6"><input type="text" name="SalesForm[total_MRC_per_order]" readonly="" class="form-control input_style input_dis" value="<?= (!empty($model->MRC) && !empty($model->QTY)) ? $model->MRC * $model->QTY : '-' ?>" id="usr"></div>
                            </div>
                            <div class="row custrow"> 
                                <div class="col-xs-6 custlabel_2">Total F.L.P. Per Order:</div>
                                <div class="col-xs-6"><input type="text" name="SalesForm[total_FLP_per_order]" readonly="" class="form-control input_style input_dis" value="<?= (!empty($model->MRC) && !empty($model->QTY)) ? $model->four_link_points * $model->QTY : '-' ?>" id="usr"></div>
                            </div>

                        </div> 

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8"></div>

                <div class="col-md-4">
                    <div>
                        <!--<a href="#" class="save-btn-2">Save</a>-->
                        <?= Html::submitButton('Save', ['id' => 'saveBtn', 'class' => 'save-btn-2']) ?>
                    </div>
                </div>
            </div>   
            <?php ActiveForm::end(); ?>

        </div>	

    </div>

</div>
<?php

function getEstDate($model, $est) {
    $date = '-';
    if ($model->submitted) {
        $date = $model->submitted;
        $date = str_replace('/', '-', $date);
        $date = new DateTime($date);
        if ($model->require_finance == '1') {
            $date->add(new DateInterval('P' . $est["est_finance"] . 'D'));
        }
        if ($model->require_account_transfer == '1') {
            $date->add(new DateInterval('P' . $est["est_AT"] . 'D'));
        }
        if ($model->require_logistic_dep == '1') {
            $date->add(new DateInterval('P' . $est["est_LD"] . 'D'));
        }
        if ($model->require_resolver_group == '1') {
            $date->add(new DateInterval('P' . $est["est_RG"] . 'D'));
        }
        return $date;
    }
    return $date;
}

function getRenewalDate($model) {
    $date = '-';
    if ($model->order_state == 'Activated') {
        $date = date('d/m/Y', strtotime($model->date_of_order_state));
        $date = str_replace('/', '-', $date);
        $date = new DateTime($date);
        $date->add(new DateInterval('P' . $model->contract_period . 'D'));
        return $date;
    }
    return $date;
}
?>
