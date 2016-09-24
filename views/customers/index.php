<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use \app\common\models\User;
use yii\widgets\ActiveForm;
use app\models\Categories;

$this->title = 'Customer';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/js/customer.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$baseUrl = Yii::$app->request->baseUrl . '/';
?>
<style>
    .save_icon {
        padding: 5px 0 6px 4px ;}
    </style>
    <div class="page-content">
    <div class="row mg-top-o">
        <ul class="nav nav-tabs nav-tabs-wraper">
            <li role="presentation"><a href="<?= Yii::$app->urlManager->createUrl("dashboard/"); ?>">Dashboard</a></li>
            <li role="presentation" ><a href="<?= Yii::$app->urlManager->createUrl("sales/"); ?>">Sales</a></li>
            <li role="presentation"><a href="<?= Yii::$app->urlManager->createUrl("executives/"); ?>">Sales Executives</a></li>
            <li role="presentation" class="active border nav-tabs-wraper_selected"><a href="<?= Yii::$app->urlManager->createUrl("customers/"); ?>">Customers</a></li>              
        </ul>
        <div class="col-md-12 content_wraper2">

            <div class="content-box-large app-content-box-large" style="height: auto;">

                <div class="row cstname">
                    <form id="filterForm" class="form-inline" method="GET" onsubmit="filterInputs()">
                        <input type="text" class="cstname-txtbx2 cstname-img" name="nameS" placeholder="Customer Name" value="<?php echo Yii::$app->request->get('nameS'); ?>" >
                        <input id="sort" class="hidden" name="sort" value="<?php echo Yii::$app->request->get('sort'); ?>" >
                        <input type="submit" class="hidden" value="Submit">
                    </form>
                </div>
                <div class="divTable">
                    <div class="divTableBody">
                        <div class="divTableRow">
                            <div class="divTableCell th_bg row_1 first"></div>
                            <div class="divTableCell th_bg row_2 text-center">UID<a href="javascript:sort('<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'customer_id') ? 'customer_id' : '-customer_id' ?>')"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-customer_id' || Yii::$app->request->get('sort') != 'customer_id') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a>&nbsp;</div>  <!-- <img src="<?= $baseUrl ?>images/down.png" width="7" height="4" alt=""/> -->
                            <div class="divTableCell th_bg row_3 text-center">Customer Acc#<a href="javascript:sort('<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'customer_acc') ? 'customer_acc' : '-customer_acc' ?>')"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-customer_acc' || Yii::$app->request->get('sort') != 'customer_acc') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></div>
                            <div class="divTableCell th_bg row_4 text-center">Name<a href="javascript:sort('<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'first_name') ? 'first_name' : '-first_name' ?>')"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-first_name' || Yii::$app->request->get('sort') != 'first_name') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></div>
                            <div class="divTableCell th_bg row_5 text-center">Acc Number<a href="javascript:sort('<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'account_no') ? 'account_no' : '-account_no' ?>')"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-account_no' || Yii::$app->request->get('sort') != 'account_no') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></div>
                            <div class="divTableCell th_bg row_6 text-center">Address</div>
                            <div class="divTableCell th_bg row_7 text-center">Phone</div>
                            <div class="divTableCell th_bg row_8 text-center">Sales Executive</div>
                            <div class="divTableCell th_bg row_9 text-center">Sales Executive Phone</div>
                            <!--<div class="divTableCell th_bg row_9">&nbsp;</div>-->  
                        </div>
                        <!-- create customer -->

                        <?php
                        if (true) {
                            $form = ActiveForm::begin([//'action' => Yii::$app->urlManager->createUrl("user/create"),
                                        'fieldConfig' => ['template' => "{input}{error}"],
                                        'validationUrl' => Yii::$app->urlManager->createUrl("customers/validation?s=default"),
                                        //'enableAjaxValidation' => true,
                                        //'enableClientValidation' => true,
                                        'options' => ['id' => 'create-form', 'class' => 'divTableRowblue']]);
                            ?>
                            <div class="divTableCell first">
                                <?= Html::submitButton('', ['class' => '', 'style' => 'background: url(' . $baseUrl . 'images/save.png) no-repeat center center; width:100%; height:23px;border:0']) ?>
                                <!--<a href="#"><img src="images/save.png" class="save_icon" /></a>-->
                            </div>
                            <div class="divTableCell"><span>&nbsp;</span></div>
                            <?= $form->field($model, 'customer_acc', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control'],'enableAjaxValidation' => true])->textInput() ?>
                            <?= $form->field($model, 'first_name', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->textInput() ?>
                            <?= $form->field($model, 'account_no', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->textInput() ?>
                            <?= $form->field($model, 'address', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => '']])->textarea() ?>
                            <?= $form->field($model, 'phone', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->textInput() ?>
                            <?= $form->field($model, 'sales_agent', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'selectpicker form-control form-margin', 'data-show-subtext' => "true", 'style' => 'padding:0px;', 'onChange' => 'getExecutive(this)']])->dropDownList($agentList, ['prompt' => 'Select']) ?>
                            <?= $form->field($model, 'agent_phone', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->textInput() ?>
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
                                //$userList = \app\components\GlobalFunction::getAgentList();
                                ?>
                                <div id="<?= $d->_id . 'D' ?>" class="divTableRow data" ondblclick="edit('<?= $d->_id ?>');"> <!--$('#<?= $d->_id . 'E' ?>').removeClass('hidden');$('#<?= $d->_id . 'D' ?>').addClass('hidden');-->
                                    <div class="divTableCell cel-padding first vertical-align"><a href="javascript:;" onclick="edit('<?= $d->_id ?>');"><img src="<?= $baseUrl ?>images/edit_icon.png" class="save_icon"/></a></div>
                                    <div class="divTableCell cel-padding text-center vertical-align"><span><a title="See Detail" href="<?= Yii::$app->urlManager->createUrl("customers/detail?id=".$d->_id); ?>">C<?= $d->customer_id ?></a></span></div>
                                    <div id="customer_acc" class="divTableCell cel-padding text-center vertical-align"><?= $d->customer_acc ?></div>
                                    <div id="first_name" class="divTableCell cel-padding text-center vertical-align"><?= $d->first_name ?></div>
                                    <div id="account_no" class="divTableCell cel-padding text-center vertical-align"><?= $d->account_no ?></div>
                                    <div id="address" class="divTableCell cel-padding text-center vertical-align"><?= $d->address ?></div>
                                    <div id="phone" class="divTableCell cel-padding text-center vertical-align"><?= $d->phone ?></div>
                                    <div id="sales_agent" class="divTableCell cel-padding text-center vertical-align"><?= (!is_array($d->sales_agent)) ? (isset($agentList[$d->sales_agent])? $agentList[$d->sales_agent] : $d->sales_agent ): $d->sales_agent['name'] ?></div>
                                    <div id="agent_phone" class="divTableCell cel-padding text-center vertical-align"><?= $d->agent_phone ?></div>
                                </div>
                                <?php
                                if (true) {
                                    $form = ActiveForm::begin([//'action' => Yii::$app->urlManager->createUrl("user/create"),
                                                'fieldConfig' => ['template' => "{input}{error}"],
                                                'validationUrl' => Yii::$app->urlManager->createUrl("customers/validation?s=default"),
                                                //'enableAjaxValidation' => true,
                                                'options' => ['id' => $d->_id . 'E', 'class' => 'divTableRow hidden form-div', 'hidden' => '']]);
                                    $modelu->attributes = $d->attributes;
                                    ?>
                                    <div class="divTableCell first">
                                        <?= Html::submitButton('', ['class' => '', 'style' => 'background: url(' . $baseUrl . 'images/save.png) no-repeat center center; width:100%; height:23px;border:0']) ?>
                                        <!--<a href="#"><img src="images/save.png" class="save_icon" /></a>-->
                                    </div>
                                    <div class="divTableCell"><span>C<?= $d->customer_id ?></span></div>
                                    <?= $form->field($modelu, '_id', ['options' => ['class' => 'divTableCell hidden'], 'inputOptions' => ['class' => 'form-control hidden']])->textInput() ?>
                                    <?= $form->field($modelu, 'customer_acc', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control'], 'enableAjaxValidation' => true])->textInput() ?>
                                    <?= $form->field($modelu, 'first_name', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->textInput() ?>
                                    <?= $form->field($modelu, 'account_no', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->textInput() ?>
                                    <?= $form->field($modelu, 'address', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => '',]])->textarea() ?>
                                    <?= $form->field($modelu, 'phone', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->textInput() ?>
                                    <?= $form->field($modelu, 'sales_agent', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'selectpicker form-control form-margin', 'data-show-subtext' => "true", 'style' => 'padding:0px;', 'onChange' => 'getExecutive(this)']])->dropDownList($agentList, ['prompt' => 'Select']) ?>
                                    <?= $form->field($modelu, 'agent_phone', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->textInput() ?>
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
                        <h1 style="text-align: center">No records found</h1>
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