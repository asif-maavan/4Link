<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use \app\common\models\User;
use yii\widgets\ActiveForm;
use app\models\Categories;

$this->title = 'Customer Detail';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/js/customer.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$baseUrl = Yii::$app->request->baseUrl . '/';
?>
<style>
    .save_icon {
        padding: 5px 0 6px 4px ;
    }
    .form-control {
        margin: 0px !important;
    }

    .docupload{
        height: auto;
        min-height: 60px;
    }
    .docupload .help-block{
        float: right;
        /*margin-right: -40px;*/
        width: 269px;
    }

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

            <div class="content-box-large2 app-content-box-large" style="height: auto;">
                <?php
                $form = ActiveForm::begin([//'action' => Yii::$app->urlManager->createUrl("user/create"),
                            'fieldConfig' => ['template' => "{input}{error}"],
                            //'validationUrl' => Yii::$app->urlManager->createUrl("customers/update-validation"),
                            //'enableAjaxValidation' => true,
                            'options' => ['id' => 'detailForm', 'class' => '']]);
                ?>
                <?= $form->field($model, '_id', ['options' => ['class' => 'divTableCell hidden'], 'inputOptions' => ['class' => 'form-control hidden']])->textInput() ?>

                <div class="row">
                    <div class="back_btn2">
                        <a href="<?php echo Yii::$app->request->referrer; ?>"><img src="<?= $baseUrl ?>images/back.png" width="43" height="12" alt=""></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="camp-info2"><span></span>Customer Info</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="row custrow"> 
                            <div class="col-xs-6 custlabel">UID</div>
                            <div class="col-xs-6 custlabel"><span>C<?= $model->customer_id ?></span></div>
                        </div>
                        <div class="row custrow"> 
                            <div class="col-xs-6 custlabel">First:</div>
                            <!--<div class="col-xs-6"><input type="text" value="William" class="form-control input_style" id="usr"></div>-->
                            <?= $form->field($model, 'first_name', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style']])->textInput() ?>
                        </div>
                        <div class="row custrow"> 
                            <div class="col-xs-6 custlabel">Last:</div>
                            <!--<div class="col-xs-6"><input type="text" value="Jackson" class="form-control input_style" id="usr"></div>-->
                            <?= $form->field($model, 'last_name', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style']])->textInput() ?>
                        </div>
                        <div class="row custrow"> 
                            <div class="col-xs-6 custlabel">Address:</div>
                            <!--<div class="col-xs-6"><input type="text" class="form-control input_style" id="usr"></div>-->
                            <?= $form->field($model, 'address', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style']])->textInput() ?>
                        </div>
                        <div class="row custrow"> 
                            <div class="col-xs-6 custlabel">&nbsp;</div>
                            <!--<div class="col-xs-6"><input type="text" class="form-control input_style" id="usr"></div>-->
                            <?= $form->field($model, 'address2', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style']])->textInput() ?>
                        </div>
                        <div class="row custrow"> 
                            <div class="col-xs-6 custlabel">City:</div>
                            <!--<div class="col-xs-6"><input type="text" class="form-control input_style" id="usr"></div>-->
                            <?= $form->field($model, 'city', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style']])->textInput() ?>
                        </div>
                        <div class="row custrow"> 
                            <div class="col-xs-6 custlabel">Country:</div>
                            <!--                            <div class="col-xs-6">
                                                            <span class="arrow" ></span>
                                                            <select class="form-control input_style" id="sel2">
                                                                <option>1</option>
                                                            </select>
                                                        </div>-->
                            <?= $form->field($model, 'country', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style']])->dropDownList($countryList, ['options' => [($model->country != "" ? $model->country : "US" ) => ['Selected' => 'selected']]]) ?>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="row custrow"> 
                            <div class="col-xs-6 custlabel">&nbsp;</div>
                            <div class="col-xs-6">&nbsp;</div>
                        </div>
                        <div class="row custrow"> 
                            <div class="col-xs-6 custlabel">Customer Acc#:</div>
                            <!--<div class="col-xs-6"><input type="text" class="form-control input_style" id="usr"></div>-->
                            <?= $form->field($model, 'customer_acc', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style']])->textInput() ?>
                        </div>
                        <div class="row custrow"> 
                            <div class="col-xs-6 custlabel">Account No :</div>
                            <!--<div class="col-xs-6"><input type="text" class="form-control input_style" id="usr"></div>-->
                            <?= $form->field($model, 'account_no', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style']])->textInput() ?>
                        </div>
                        <div class="row custrow"> 
                            <div class="col-xs-6 custlabel">Email:</div>
                            <!--<div class="col-xs-6"><input type="text" class="form-control input_style" id="usr"></div>-->
                            <?= $form->field($model, 'email', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style']])->textInput() ?>
                        </div>
                        <div class="row custrow"> 
                            <div class="col-xs-6 custlabel">Phone:</div>
                            <!--<div class="col-xs-6"><input type="text" class="form-control input_style" id="usr"></div>-->
                            <?= $form->field($model, 'phone', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style']])->textInput() ?>
                        </div>
                        <div class="row custrow"> 
                            <div class="col-xs-6 custlabel">Zip:</div>
                            <!--<div class="col-xs-6"><input type="text" class="form-control input_style" id="usr"></div>-->
                            <?= $form->field($model, 'zip', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style']])->textInput() ?>
                        </div>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="camp-info2"><span></span>Sales Executive Info</div>
                    </div>
                </div>
                <div class="row "><!--margin-left-none-->
                    <div class="col-xs-6">
                        <div class="row custrow"> 
                            <div class="col-xs-6 custlabel">Name:</div>
                            <!--<div class="col-xs-6"><input type="text" class="form-control input_style" id="usr"></div>-->
                            <?= $form->field($model, 'sales_agent', ['options' => ['class' => 'col-xs-6'], 'inputOptions' => ['class' => 'form-control input_style', 'onChange' => 'getExecutive(this)']])->dropDownList($agentList, ['prompt' => 'Select']) ?>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="row custrow"> 
                            <div class="col-xs-6 custlabel">Phone:</div>
                            <div id="agent_phone" class="col-xs-6 custlabel"><?= $model->agent_phone ?></div>
                            <?= $form->field($model, 'agent_phone', ['options' => ['class' => 'col-xs-6 hidden'], 'inputOptions' => ['class' => 'form-control input_style']])->textInput() ?>
                        </div>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-xs-12">
                        <div class="camp-info2"><span></span>Documents</div>
                    </div>
                </div>
                <div class="row docupload margin-left-none">
                    <div class="col-xs-6 col-md-4">&nbsp;</div>
                    <!--<div class="col-xs-6 col-md-4"><input type="text" class="form-control input_style2" style="margin:12px -40px 0px 0px !important;"placeholder="" value="Document Name" id="usr"></div>-->
                    <?= $form->field($documentModel, 'fileName', ['options' => ['class' => 'col-xs-6 col-md-4'], 'inputOptions' => ['class' => 'form-control input_style2', 'style' => "margin:12px -40px 0px 0px !important;color:black", 'placeholder' => 'Document Name']])->textInput() ?>
                    <div class="col-xs-6 col-md-4"><a href="javascript:;" class="selectfile-btn" onclick="upload();"><img src="<?= $baseUrl ?>images/selectfile-btn.png" alt=""/></a></div>
                    <?= $form->field($documentModel, 'pdfFile', ['options' => ['class' => 'upload-img'], 'inputOptions' => [ 'class' => 'custom-file-input', 'style' => 'display:none;', 'accept' => 'application/pdf']])->fileInput()->label(false); ?>
                </div>
                <div id="documents">
                    <?php
                    if (count($documents) > 0) {
                        foreach ($documents as $doc) {
                            ?>
                            <div id="doc-<?= $doc['id'] ?>" class="row uploaded-files">
                                <div class="col-sm-1" style="width:60px !important;"><img src="<?= $baseUrl ?>images/doc-icon1.png" class="uploaded-files-img2" alt=""></div>
                                <div class="col-sm-5" style="width: 36% !important;"><?= $doc['name'] ?></div>
                                <div class="col-sm-4 width-fix"><img src="<?= $baseUrl ?>images/doc-icon2.png" class="uploaded-files-img2" alt=""><?= date('M d, Y', strtotime($doc['date'])) ?></div>
                                <div class="col-sm-1 uploaded-files-img"><a href="<?= $baseUrl ?>uploads/documents/<?= $doc['file'] ?>" download><img src="<?= $baseUrl ?>images/doc-icon3.png" alt=""></a></div>
                                <div class="col-sm-1 uploaded-files-img"><a href="<?= $baseUrl ?>uploads/documents/<?= $doc['file'] ?>"><img src="<?= $baseUrl ?>images/doc-icon4.png" width="23" height="27" alt=""></a></div>
                                <div class="col-sm-1 uploaded-files-img"><a href="javascript:;" onclick="rmvdoc('<?= $doc['id'] ?>')"><img src="<?= $baseUrl ?>images/doc-icon5.png" width="23" height="27" alt=""></a></div>
                            </div> 

                            <?php
                        }
                    } else {
                        ?>
                    <h3 id="ndf" class="text-center">No Document Found</h3>

                    <?php }
                    ?>

                </div>
                <div class="row ">
                    <div class="col-xs-12">
                        <!--<a href="#" class="submit-btn">Update</a>-->
                        <?= Html::submitButton('Update', ['class' => 'submit-btn', 'style' => 'border:0px']) ?>
                    </div>
                </div>
                <?php
                ActiveForm::end();
                ?>

            </div>

        </div>

    </div>

</div>