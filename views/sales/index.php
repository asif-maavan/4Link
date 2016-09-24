<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use \app\common\models\User;
use yii\widgets\ActiveForm;
use app\models\Categories;

$this->title = 'Sales';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/js/sales.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$baseUrl = Yii::$app->request->baseUrl . '/';
?>
<style>
    .save_icon {
        padding: 5px 0 6px 4px ;}
    .cel-padding{
        word-break: break-word;
    }
    .submit-date-btn{
        width: 100%;
        height: 34px;
        font-size: 12px;
        padding: 6px 5px;
    }
</style>
<div class="page-content">
    <div class="row mg-top-o">
        <ul class="nav nav-tabs nav-tabs-wraper">
            <li role="presentation"><a href="<?= Yii::$app->urlManager->createUrl("dashboard/"); ?>">Dashboard</a></li>
            <li role="presentation"  class="active border nav-tabs-wraper_selected"><a href="<?= Yii::$app->urlManager->createUrl("sales/"); ?>">Sales</a></li>
            <li role="presentation"><a href="<?= Yii::$app->urlManager->createUrl("executives/"); ?>">Sales Executives</a></li>
            <li role="presentation"><a href="<?= Yii::$app->urlManager->createUrl("customers/"); ?>">Customers</a></li>              
        </ul>
        <div class="col-md-12 content_wraper2">

            <div class="content-box-large app-content-box-large" style="height: auto;">

                <div class="row cstname">
                    <form id="filterForm" class="form-inline" method="get" style="display: flex;" onsubmit="filterInputs()">
                        <input type="text" class="cstname-txtbx2" style="padding-right: 10px;" name="indexS" placeholder="Index#" value="<?php echo Yii::$app->request->get('indexS'); ?>" >
                        <label for="exampleInputName2 888" >Order State: </label>
                        <select name="status" class="cstname-txtbx2" style="margin-left: 10px;color: #000;padding-right: 10px;">
                            <option value="">All</option>
                            <?php foreach ($orderStateList as $key => $value) { ?>
                                <option value="<?= $key ?>" <?= (Yii::$app->request->get('status') == $key) ? 'selected' : '' ?>> <?= $value ?></option>
                            <?php } ?>
                        </select>
                        <input id="sort" class="hidden" name="sort" value="<?php echo Yii::$app->request->get('sort'); ?>" >
                        <span class="btn icon-btn" onclick="$('#filterForm').submit();" style="margin: 6px 0 18px 48px;border: 0;height: 29px;width: 37px"><img src="<?= $baseUrl ?>images/search.png"></span>
                        <input type="submit" class="hidden" value="Submit">
                    </form>
                </div>
                <div class="divTable">
                    <div class="divTableBody">
                        <div class="divTableRow">
                            <div class="divTableCell th_bg row_1 first" style="width: 44px !important"></div>
                            <div class="divTableCell th_bg row_2 text-center">UID<a href="javascript:sort('<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'uid') ? 'uid' : '-uid' ?>')"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-uid' || Yii::$app->request->get('sort') != 'uid') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a>&nbsp;</div>  <!-- <img src="<?= $baseUrl ?>images/down.png" width="7" height="4" alt=""/> -->
                            <div class="divTableCell th_bg row_7 text-center">Index#<a href="javascript:sort('<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'index_no') ? 'index_no' : '-index_no' ?>')"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-index_no' || Yii::$app->request->get('sort') != 'index_no') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></div>
                            <div class="divTableCell th_bg row_4 text-center">Sales Executive<a href="javascript:sort('<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'sale_executive.name') ? 'sale_executive.name' : '-sale_executive.name' ?>')"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-sale_executive.name' || Yii::$app->request->get('sort') != 'sale_executive.name') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></div>
                            <div class="divTableCell th_bg row_9 text-center" style="width: 85px !important">Customer Type<a href="javascript:sort('<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'customer_type') ? 'customer_type' : '-customer_type' ?>')"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-customer_type' || Yii::$app->request->get('sort') != 'customer_type') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></div>
                            <div class="divTableCell th_bg row_9 text-center">Order Type</div>
                            <div class="divTableCell th_bg row_8 text-center">Customer Name</div>
                            <div class="divTableCell th_bg row_7 text-center">Customer Acc#</div>
                            <div class="divTableCell th_bg row_9 text-center">Plan</div>
                            <div class="divTableCell th_bg row_7 text-center">Siebel Activity#</div>
                            <div class="divTableCell th_bg row_9 text-center" style="width: 79px !important">Require Finance</div>
                            <div class="divTableCell th_bg row_9 text-center" style="width: 74px !important">Require Account Transfer</div>
                            <div class="divTableCell th_bg row_9 text-center">Submitted to A.T</div>
                            <div class="divTableCell th_bg row_9 text-center">SO Number</div>
                            <div class="divTableCell th_bg row_9 text-center">Order State</div>

                        </div>
                        <!-- create customer -->

                        <?php
                        if (true) {
                            $form = ActiveForm::begin([//'action' => Yii::$app->urlManager->createUrl("user/create"),
                                        'fieldConfig' => ['template' => "{input}{error}"],
                                        'validationUrl' => Yii::$app->urlManager->createUrl("sales/validation?s=create"),
                                        //'enableAjaxValidation' => true,
                                        //'enableClientValidation' => true,
                                        'options' => ['id' => 'create-form', 'class' => 'divTableRowblue']]);
                            ?>
                            <div class="divTableCell first">
                                <?= Html::submitButton('', ['class' => '', 'style' => 'background: url(' . $baseUrl . 'images/save.png) no-repeat center center; width:100%; height:23px;border:0']) ?>
                            </div>
                            <div class="divTableCell"><span>&nbsp;</span></div>
                            <?= $form->field($model, 'index_no', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control'], 'enableAjaxValidation' => true])->textInput() ?>
                            <?= $form->field($model, 'sale_executive', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'selectpicker form-control form-margin', 'data-show-subtext' => "true", 'style' => 'padding:0px;', 'onChange' => '']])->dropDownList($agentList, ['prompt' => 'Select']) ?>
                            <?= $form->field($model, 'customer_type', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'selectpicker form-control form-margin', 'data-show-subtext' => "true", 'style' => 'padding:0px;', 'onChange' => 'switchC(this)']])->dropDownList($customerTypeList, ['prompt' => 'Select']) ?>
                            <?= $form->field($model, 'order_type', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'selectpicker form-control form-margin', 'data-show-subtext' => "true", 'style' => 'padding:0px;', 'onChange' => '']])->dropDownList($orderTypeList, ['prompt' => 'Select']) ?>
                            <?= $form->field($model, 'customer_name', ['options' => ['class' => 'divTableCell', 'id' => 'customer'], 'inputOptions' => ['class' => 'selectpicker form-control form-margin', 'data-show-subtext' => "true", 'style' => 'padding:0px;', 'onChange' => 'getC(this)'], 'enableAjaxValidation' => true])->dropDownList($customerList, ['prompt' => 'Select']) ?>
                            <?= $form->field($model, 'customer_acc_no', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control', 'readonly' => '']])->textInput() ?>
                            <?= $form->field($model, 'plan', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'selectpicker form-control form-margin', 'data-show-subtext' => "true", 'style' => 'padding:0px;', 'onChange' => '']])->dropDownList($planList, ['prompt' => 'Select']) ?>
                            <?= $form->field($model, 'siebel_activity_no', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->textInput() ?>
                            <?= $form->field($model, 'require_finance', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'selectpicker form-control form-margin', 'data-show-subtext' => "true", 'style' => 'padding:0px;', 'onChange' => '']])->dropDownList($YN, ['prompt' => 'Select']) ?>
                            <?= $form->field($model, 'require_account_transfer', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'selectpicker form-control form-margin', 'data-show-subtext' => "true", 'style' => 'padding:0px;', 'onChange' => 'switchSAT(this)']])->dropDownList($YN, ['prompt' => 'Select']) ?>
                            <div id="submitted_to_AT" class="divTableCell cel-padding text-center vertical-align">-</div>
                            <?= ''; //$form->field($model, 'submitted_to_AT', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control', 'readonly' => '']])->textInput() ?>
                            <div id="so_no" class="divTableCell cel-padding text-center vertical-align">-</div>
                            <?='';// $form->field($model, 'so_no', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control'], 'enableAjaxValidation' => true])->textInput() ?>
                            <?= $form->field($model, 'order_state', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'selectpicker form-control form-margin', 'data-show-subtext' => "true", 'style' => 'padding:0px;', 'onChange' => '']])->dropDownList($orderStateList, ['prompt' => 'Select']) ?>
                            <!--<div class="divTableCell">&nbsp;</div>-->
                            <?php
                            ActiveForm::end();
                        }
                        ?>
                        <!-- end create -->

                        <!-- records -->
                        <?php
                        if (count($data) > 0) {
                            foreach ($data as $d) {
                                ?>
                                                                                                        <div id="<?= $d->_id . 'D' ?>" class="divTableRow data" ondblclick="edit('<?= $d->_id ?>');"> <!--$('#<?= $d->_id . 'E' ?>').removeClass('hidden');$('#<?= $d->_id . 'D' ?>').addClass('hidden');-->
                                    <div class="divTableCell cel-padding first vertical-align"><a href="javascript:;" onclick="edit('<?= $d->_id ?>');"><img src="<?= $baseUrl ?>images/edit_icon.png" class="save_icon"/></a></div>
                                    <div class="divTableCell cel-padding text-center vertical-align"><a title="See Detail" href="<?= Yii::$app->urlManager->createUrl("sales/detail?id=" . $d->_id); ?>">S<?= $d->uid ?></a></div>
                                    <div id="index_no" class="divTableCell cel-padding text-center vertical-align"><?= $d->index_no ?></div>
                                    <div id="sale_executive" class="divTableCell cel-padding text-center vertical-align"><?= $d->sale_executive['name'] ?></div>
                                    <div id="customer_type" class="divTableCell cel-padding text-center vertical-align"><?= $customerTypeList[$d->customer_type] ?></div>
                                    <div id="order_type" class="divTableCell cel-padding text-center vertical-align"><?= $d->order_type['name'] ?></div>
                                    <div id="customer_name" class="divTableCell cel-padding text-center vertical-align"><?= $d->customer_name['name'] ?></div>
                                    <div id="customer_acc_no" class="divTableCell cel-padding text-center vertical-align"><?= $d->customer_acc_no ?></div>
                                    <div id="plan" class="divTableCell cel-padding text-center vertical-align"><?= $d->plan['name'] ?></div>
                                    <div id="siebel_activity_no" class="divTableCell cel-padding text-center vertical-align"><?= $d->siebel_activity_no ?></div>
                                    <div id="require_finance" class="divTableCell cel-padding text-center vertical-align"><?= isset($YN[$d->require_finance]) ? $YN[$d->require_finance] : '-' ?></div>
                                    <div id="require_account_transfer" class="divTableCell cel-padding text-center vertical-align"><?= isset($YN[$d->require_account_transfer]) ? $YN[$d->require_account_transfer] : '-' ?></div>
                                    <div id="submitted_to_AT" class="divTableCell cel-padding text-center vertical-align"><?= (isset($d->submitted_to_AT->sec)) ? date('d/m/Y', $d->submitted_to_AT->sec) : '-' ?></div>
                                    <div id="so_no" class="divTableCell cel-padding text-center vertical-align"><?= $d->so_no ?></div>
                                    <div id="order_state" class="divTableCell cel-padding text-center vertical-align"><?= $d->order_state ?></div>
                                </div>
                                <?php
                                if (true) {
                                    $form = ActiveForm::begin([//'action' => Yii::$app->urlManager->createUrl("user/create"),
                                                'fieldConfig' => ['template' => "{input}{error}"],
                                                'validationUrl' => Yii::$app->urlManager->createUrl("sales/validation?s=update"),
                                                //'enableAjaxValidation' => true,
                                                'options' => ['id' => $d->_id . 'E', 'class' => 'divTableRow hidden form-div', 'hidden' => '']]);
                                    $modelu->attributes = $d->attributes;
                                    $isAT = ($modelu->require_account_transfer == '1') ? TRUE : FALSE;
                                    ?>
                                    <div class="divTableCell first">
                                        <?= Html::submitButton('', ['class' => '', 'style' => 'background: url(' . $baseUrl . 'images/save.png) no-repeat center center; width:100%; height:23px;border:0']) ?>
                                    </div>
                                    <div class="divTableCell">S<?= $d->uid ?></div>
                                    <?= $form->field($modelu, '_id', ['options' => ['class' => 'divTableCell hidden'], 'inputOptions' => ['class' => 'form-control hidden']])->textInput() ?>
                                    <?= $form->field($modelu, 'index_no', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control'], 'enableAjaxValidation' => true])->textInput() ?>
                                    <?= $form->field($modelu, 'sale_executive', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'selectpicker form-control form-margin', 'data-show-subtext' => "true", 'style' => 'padding:0px;', 'onChange' => '']])->dropDownList($agentList, ['prompt' => 'Select']) ?>
                                    <?= $form->field($modelu, 'customer_type', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'selectpicker form-control form-margin', 'data-show-subtext' => "true", 'style' => 'padding:0px;', 'onChange' => 'switchC(this)']])->dropDownList($customerTypeList, ['prompt' => 'Select']) ?>
                                    <?= $form->field($modelu, 'order_type', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'selectpicker form-control form-margin', 'data-show-subtext' => "true", 'style' => 'padding:0px;', 'onChange' => '']])->dropDownList($orderTypeList, ['prompt' => 'Select']) ?>
                                    <?= $form->field($modelu, 'customer_name', ['options' => ['class' => 'divTableCell', 'id' => 'customer'], 'inputOptions' => ['class' => 'selectpicker form-control form-margin', 'data-show-subtext' => "true", 'style' => 'padding:0px;', 'onChange' => 'getC(this)'], 'enableAjaxValidation' => true])->dropDownList($customerList, ['prompt' => 'Select']) ?>
                                    <?= $form->field($modelu, 'customer_acc_no', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control', 'readonly' => '']])->textInput() ?>
                                    <?= $form->field($modelu, 'plan', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'selectpicker form-control form-margin', 'data-show-subtext' => "true", 'style' => 'padding:0px;', 'onChange' => '']])->dropDownList($planList, ['prompt' => 'Select']) ?>
                                    <?= $form->field($modelu, 'siebel_activity_no', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->textInput() ?>
                                    <?= $form->field($modelu, 'require_finance', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'selectpicker form-control form-margin', 'data-show-subtext' => "true", 'style' => 'padding:0px;', 'onChange' => '']])->dropDownList($YN, ['prompt' => 'Select']) ?>
                                    <?= $form->field($modelu, 'require_account_transfer', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'selectpicker form-control form-margin', 'data-show-subtext' => "true", 'style' => 'padding:0px;', 'onChange' => 'switchSAT(this)']])->dropDownList($YN, ['prompt' => 'Select']) ?>
                                    <div id="submitted_to_AT" class="divTableCell cel-padding text-center vertical-align">
                                        <?php if ($isAT) { ?>
                                            <a id="<?= $d->uid ?>-submitted_to_finance"  href="javascript:;" class="btn btn-primary submit-date-btn" <?= (!empty($modelu->submitted_to_AT)) ? 'disabled' : '' ?> <?= empty($modelu->submitted_to_AT) ? 'onclick="submitTo(\'AT\', \'' . $modelu->_id . '\');"' : '' ?> > <?= (!empty($modelu->submitted_to_AT)) ? '<b>Submitted</b>' : '<b>Submit</b>' ?> </a>
                                            <?php
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </div>       <?= ''; // $form->field($modelu, 'submitted_to_AT', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control ' . $datePicker, 'id' => $d->_id . '-submitted_to_at', 'readonly' => ($datePicker) ? false : '', 'value'=>'' ]])->textInput()       ?>
                                    <?= $form->field($modelu, 'so_no', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control', 'onchange'=>'soAssigned(\''.$modelu->_id.'\')'], 'enableAjaxValidation' => true])->textInput() ?>
                                    <?= $form->field($modelu, 'order_state', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'selectpicker form-control form-margin', 'data-show-subtext' => "true", 'style' => 'padding:0px;', 'onChange' => '']])->dropDownList($orderStateList, ['prompt' => 'Select']) ?>
                                    <?php
                                    ActiveForm::end();
                                }
                                ?>
                            <?php } ?>
                        </div>

                    </div><!-- DivTable.com -->
                    <?php
                } else {
                    echo '</div></div>'
                    ?>
                    <div class="noOrder">
                        <h1 style="text-align: center">No Records Found</h1>
                    </div>
                <?php } ?>
                <div class="row">
                    <?php
                    if (isset($pagination)) {
                        echo LinkPager::widget([
                            'pagination' => $pagination,
                            'options' => ['class' => 'pagination pagination_margin'],
                        ]);
                    }
                    ?>

                </div>

            </div>

        </div>

    </div>

</div>