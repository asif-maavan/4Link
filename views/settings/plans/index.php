<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use \app\common\models\User;
use yii\widgets\ActiveForm;
use app\models\Categories;

$this->title = 'User Management';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/js/plan.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$baseUrl = Yii::$app->request->baseUrl . '/';
?>

<!--<script src="<?php echo Yii::$app->request->baseUrl; ?>/js/user.js"></script>-->
<div class="row">
    <div class="col-md-2 leftbar ">
        <div class="sidebar content-box" style="display: block;">
            <div  class="back_btn">
                <a href="<?php echo Yii::$app->request->referrer; ?>"><img src="<?= $baseUrl ?>images/back.png" width="43" height="12" alt=""/></a>
            </div>
            <ul class="nav">
                <!-- Main menu -->
                <?php if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) { ?><li class="select"><a href="<?= Yii::$app->urlManager->createUrl("settings/plans/"); ?>">Plans</a></li> <?php } ?>
                <li><a href="<?= Yii::$app->urlManager->createUrl("user/my-account/"); ?>">My Account</a></li>
                <?php if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) { ?><li><a href="<?= Yii::$app->urlManager->createUrl("user/"); ?>">Users</a></li> <?php } ?>
                <?php if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) { ?><li><a href="<?= Yii::$app->urlManager->createUrl("settings/values/"); ?>">Values</a></li><?php } ?>
            </ul>
        </div>
    </div>
    <div class="col-md-10 content_wraper">
        <div class="content-box-large" style="height: auto;min-height: 651px;">
            <div class="divTable">
                <div class="divTableBody">
                    <div class="divTableRow">
                        <div class="divTableCell th_bg row4 first"></div>
                        <div class="divTableCell th_bg row4 ">Plans<a href="?sort=<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'name') ? 'name' : '-name' ?>"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-name' || Yii::$app->request->get('sort') != 'name') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></div>
                        <div class="divTableCell th_bg row5">Plan Group<a href="?sort=<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'plan_group') ? 'plan_group' : '-plan_group' ?>"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-plan_group' || Yii::$app->request->get('sort') != 'plan_group') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></div>
                        <div class="divTableCell th_bg row5">Plan Type<a href="?sort=<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'plan_type') ? 'plan_type' : '-plan_type' ?>"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-plan_type' || Yii::$app->request->get('sort') != 'plan_type') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></div>
                        <div class="divTableCell th_bg row4">Contract Period<a href="?sort=<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'contract_period') ? 'contract_period' : '-contract_period' ?>"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-contract_period' || Yii::$app->request->get('sort') != 'contract_period') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></div>
                        <div class="divTableCell th_bg row4">MRC<a href="?sort=<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'mrc') ? 'mrc' : '-mrc' ?>"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-mrc' || Yii::$app->request->get('sort') != 'mrc') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></div>
                        <div class="divTableCell th_bg row4">4Link Points Multiplier<a href="?sort=<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'fourlink_points') ? 'fourlink_points' : '-fourlink_points' ?>"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-fourlink_points' || Yii::$app->request->get('sort') != 'fourlink_points') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></div>
                    </div>
                    <!--/....................... user create form -->
                    <?php
                    if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                        $form = ActiveForm::begin([//'action' => Yii::$app->urlManager->createUrl("user/create"),
                                    'fieldConfig' => ['template' => "{input}{error}"],
                                    //'validationUrl' => Yii::$app->urlManager->createUrl("user/create-validation"),
                                    //'enableAjaxValidation' => true,
                                    //'enableClientValidation' => true,
                                    'options' => ['id' => 'create-form', 'class' => 'divTableRowblue']]);
                        ?>
                        <div class="divTableCell first">
                            <?= Html::submitButton('', ['class' => '', 'style' => 'background: url(' . $baseUrl . 'images/save.png) no-repeat center center; width:100%; height:23px;border:0']) ?>
                            <!--<a href="#"><img src="images/save.png" class="save_icon" /></a>-->
                        </div>
                        <!--<div class="divTableCell"><span>&nbsp;</span></div>-->
                        <?= $form->field($model, 'name', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->textInput() ?>
                        <?= $form->field($model, 'plan_group', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->textInput() ?>
                        <?= $form->field($model, 'plan_type', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control', 'style' => 'padding:0px;']])->dropDownList($typeList, ['prompt' => 'Select']) ?>
                        <?= $form->field($model, 'contract_period', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->textInput() ?>
                        <?= $form->field($model, 'mrc', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->textInput() ?>
                        <?= $form->field($model, 'fourlink_points', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->textInput() ?>
                        <?php
                        ActiveForm::end();
                    }
                    ?>
                    <?php
                    if (count($data) > 0) {
                        foreach ($data as $d) {
                            ?>
                            <div id="<?= $d->_id . 'D' ?>" class="divTableRow data" ondblclick="edit('<?= $d->_id ?>');">
                                <div class="divTableCell first cel-padding">
                                    <span class="btn glyphicon glyphicon-remove-circle larg_font icon-btn" onclick="removePlan('<?= $d->_id ?>')"></span>
                                    <a href="javascript:;" class="btn icon-btn" style="margin-left: 23px" onclick="edit('<?= $d->_id ?>');"><img src="<?= $baseUrl ?>images/edit_icon.png" class=""/></a>

                                </div>
                                <!--<div class="divTableCell"><span><?= ''; //$d->user_id                                       ?></span></div>-->
                                <div id="name" class="divTableCell cel-padding text-center"><?= $d->name ?></div>
                                <div id="plan_group" class="divTableCell cel-padding text-center"><?= $d->plan_group ?></div>
                                <div id="plan_type" class="divTableCell cel-padding text-center"><?= (isset($typeList[$d->plan_type])) ? $typeList[$d->plan_type] : $d->plan_type ?></div>
                                <div id="contract_period" class="divTableCell cel-padding text-center"><?= $d->contract_period ?></div>
                                <div id="mrc" class="divTableCell cel-padding text-center"><?= $d->mrc ?></div>
                                <div id="fourlink_points" class="divTableCell cel-padding text-center"><?= $d->fourlink_points ?></div>
                            </div>
                            <?php
                            if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                                $form = ActiveForm::begin([//'action' => Yii::$app->urlManager->createUrl("user/create"),
                                            'fieldConfig' => ['template' => "{input}{error}"],
                                            'validationUrl' => Yii::$app->urlManager->createUrl("user/update-validation"),
                                            //'enableAjaxValidation' => true,
                                            'options' => ['id' => $d->_id . 'E', 'class' => 'divTableRow hidden form-div', 'hidden' => '']]);
                                $modelu->attributes = $d->attributes;
                                ?>
                                <div class="divTableCell first">
                                    <?= Html::submitButton('', ['class' => '', 'style' => 'background: url(' . $baseUrl . 'images/save.png) no-repeat center center; width:100%; height:23px;border:0']) ?>
                                    <!--<a href="#"><img src="images/save.png" class="save_icon" /></a>-->
                                </div>
                                <!--<div class="divTableCell"><span><?= ''; // $d->user_id                                     ?></span></div>-->
                                <?= $form->field($modelu, '_id', ['options' => ['class' => 'divTableCell hidden'], 'inputOptions' => ['class' => 'form-control hidden', 'value' => $d->_id]])->textInput() ?>
                                <?= $form->field($modelu, 'name', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control', 'value' => $d->name]])->textInput() ?>
                                <?= $form->field($modelu, 'plan_group', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control', 'value' => $d->plan_group]])->textInput() ?>
                                <?= $form->field($modelu, 'plan_type', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control', 'style' => 'padding:0px;']])->dropDownList($typeList, ['prompt' => 'Select']) ?>
                                <?= $form->field($modelu, 'contract_period', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control', 'value' => $d->contract_period]])->textInput() ?>
                                <?= $form->field($modelu, 'mrc', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control', 'value' => $d->mrc]])->textInput() ?>
                                <?= $form->field($modelu, 'fourlink_points', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control', 'value' => $d->fourlink_points]])->textInput() ?>
                                <?php
                                ActiveForm::end();
                            }
                            ?>
                        <?php } ?>
                    </div>

                </div>
                <!-- DivTable.com -->
            <?php } else { ?>
            </div>

        </div>
        <!-- DivTable.com -->
        <div class="row">
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

