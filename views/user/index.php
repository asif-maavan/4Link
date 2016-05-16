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

<!--<script src="<?php echo Yii::$app->request->baseUrl; ?>/js/user.js"></script>-->
<div class="row">
    <div class="col-md-2 leftbar ">
        <div class="sidebar content-box" style="display: block; margin:20px 0 0 0;">
            <div  class="back_btn">
                <a href="<?php echo Yii::$app->request->referrer; ?>"><img src="<?= $baseUrl ?>images/back.png" width="43" height="12" alt=""/></a>
            </div>
            <ul class="nav">
                <!-- Main menu -->
                <?php if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                    ?><li><a href="<?= Yii::$app->urlManager->createUrl("settings/plans/"); ?>">Plans</a></li> <?php } ?>
                <li><a href="calendar.html">My Account</a></li>
                <?php if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                    ?> <li class="select"><a href="<?= Yii::$app->urlManager->createUrl("user/"); ?>">Users</a></li> <?php } ?>
                <li><a href="tables.html">Values</a></li>
            </ul>
        </div>
    </div>
    <div class="col-md-10 content_wraper">
        <div class="content-box-large" style="height: auto;min-height: 651px;">
            <div class="divTable">
                <div class="divTableBody">
                    <div class="divTableRow">
                        <div class="divTableCell th_bg row1 first"></div>
                        <div class="divTableCell th_bg row2 text-center">User&nbsp;#<a href="?sort=<?= (Yii::$app->request->get('sort')[0] == '-' || empty(Yii::$app->request->get('sort'))) ? 'user_id' : '-user_id' ?>"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-user_id' || Yii::$app->request->get('sort') != 'user_id') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></div>
                        <div class="divTableCell th_bg row3 text-center">First<a href="?sort=<?= (Yii::$app->request->get('sort')[0] == '-' || empty(Yii::$app->request->get('sort'))) ? 'first_name' : '-first_name' ?>"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort')[0] == '-first_name' || Yii::$app->request->get('sort') != 'first_name') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></div>
                        <div class="divTableCell th_bg row4 text-center">Last<a href="?sort=<?= (Yii::$app->request->get('sort')[0] == '-' || empty(Yii::$app->request->get('sort'))) ? 'last_name' : '-last_name' ?>"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort')[0] == '-last_name' || Yii::$app->request->get('sort') != 'last_name') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></div>
                        <div class="divTableCell th_bg row5 text-center">Address</div>
                        <div class="divTableCell th_bg row6 text-center">Email</div>
                        <div class="divTableCell th_bg row7 text-center">Phone</div>
                        <div class="divTableCell th_bg row8 text-center">Role<a href="?sort=<?= (Yii::$app->request->get('sort')[0] == '-' || empty(Yii::$app->request->get('sort'))) ? 'user_role' : '-user_role' ?>"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort')[0] == '-user_role' || Yii::$app->request->get('sort') != 'user_role') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></div>
                        <div class="divTableCell th_bg row9 text-center">Report To<!--<img src="<?= $baseUrl ?>images/down.png" width="7" height="4" alt=""/>--></div>
                        <div class="divTableCell th_bg row10 text-center">Password</div>
                        <div class="divTableCell th_bg row11 text-center">Confirm Password</div>
                    </div>
                    <!--/....................... user create form -->
                    <?php
                    if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
                        $form = ActiveForm::begin([//'action' => Yii::$app->urlManager->createUrl("user/create"),
                                    'fieldConfig' => ['template' => "{input}{error}"],
                                    'validationUrl' => Yii::$app->urlManager->createUrl("user/create-validation"),
                                    'enableAjaxValidation' => true,
                                    //'enableClientValidation' => true,
                                    'options' => ['id' => 'create-form', 'class' => 'divTableRowblue']]);
                        ?>
                        <div class="divTableCell first">
                            <?= Html::submitButton('', ['class' => '', 'style' => 'background: url(' . $baseUrl . 'images/save.png) no-repeat center center; width:100%; height:23px;border:0']) ?>
                            <!--<a href="#"><img src="images/save.png" class="save_icon" /></a>-->
                        </div>
                        <div class="divTableCell"><span>&nbsp;</span></div>
                        <?= $form->field($model, 'first_name', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->textInput() ?>
                        <?= $form->field($model, 'last_name', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->textInput() ?>
                        <?= $form->field($model, 'address', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->textInput() ?>
                        <?= $form->field($model, 'email', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->textInput() ?>
                        <?= $form->field($model, 'phone', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->textInput() ?>
                        <?= $form->field($model, 'user_role', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control', 'style' => 'padding:0px;', 'onChange' => 'getUsersList("new")']])->dropDownList($roleList, ['prompt' => 'Select']) ?>
                        <?= $form->field($model, 'report_to', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control', 'style' => 'padding:0px;']])->dropDownList($roleList, ['prompt' => 'Select']) ?>
                        <?= $form->field($model, 'password', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->passwordInput() ?>
                        <?= $form->field($model, 'confirm_password', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control']])->passwordInput() ?>
                        <?php
                        ActiveForm::end();
                    }
                    ?>
                    <!--                    <div class="divTableRowblue">
                                            <div class="divTableCell first"><a href="#"><img src="images/save.png" class="save_icon" /></a></div>
                                            <div class="divTableCell"><span>1</span></div>
                                            <div class="divTableCell">  <input type="text" class="form-control" value="Steve" id="usr"></div>
                                            <div class="divTableCell"><input type="text" class="form-control" value="Williams" id="usr"></div>
                                            <div class="divTableCell"><input type="text" class="form-control" value="6874 Stevenson Blvd, Fremont, CA" id="usr"></div>
                                            <div class="divTableCell"><input type="text" class="form-control" value="steve@somend.com" id="usr"></div>
                                            <div class="divTableCell"><input type="text" class="form-control" value="212-247-7800" id="usr"></div>
                                            <div class="divTableCell"><input type="text" class="form-control" value="Supervisor" id="usr"></div>
                                            <div class="divTableCell"><input type="text" class="form-control" value="Supervisor" id="usr"></div>
                                            <div class="divTableCell">  <input type="password" value="******" class="form-control" id="pwd"></div>
                                            <div class="divTableCell">  <input type="password" value="******" class="form-control" id="pwd"></div>
                                        </div>-->
                    <!--/....................... user listing -->
                    <?php
                    if (count($data) > 0) {
                        foreach ($data as $d) {
                            $userList = \app\components\GlobalFunction::getReportToList($d->_id, $d->user_role);
                            ?>
                                    <div id="<?= $d->_id . 'D' ?>" class="divTableRow data" ondblclick="edit('<?= $d->_id ?>');"> <!--$('#<?= $d->_id . 'E' ?>').removeClass('hidden');$('#<?= $d->_id . 'D' ?>').addClass('hidden');-->
                                <div class="divTableCell first"><a href="javascript:;" onclick="edit('<?= $d->_id ?>');"><img src="<?= $baseUrl ?>images/edit_icon.png" class="save_icon"/></a></div>
                                <div class="divTableCell text-center"><span><?= $d->user_id ?></span></div>
                                <div id="first_name" class="divTableCell text-center"><?= $d->first_name ?></div>
                                <div id="last_name" class="divTableCell text-center"><?= $d->last_name ?></div>
                                <div id="address" class="divTableCell text-center"><?= $d->address ?></div>
                                <div id="email" class="divTableCell text-center"><?= $d->email ?></div>
                                <div id="phone" class="divTableCell text-center"><?= $d->phone ?></div>
                                <div id="user_role" class="divTableCell text-center"><?= $roleList[$d->user_role] ?></div>
                                <div id="report_to" class="divTableCell text-center"><?= (isset($userList[$d->report_to])) ? $userList[$d->report_to] : $d->report_to ?></div>
                                <div class="divTableCell">  <input type="password" value="******" class="form-control" id="pwd" disabled=""></div>
                                <div class="divTableCell">  <input type="password" value="******" class="form-control" id="pwd" disabled=""></div>
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
                                <div class="divTableCell"><span><?= $d->user_id ?></span></div>
                                <?= $form->field($modelu, '_id', ['options' => ['class' => 'divTableCell hidden'], 'inputOptions' => ['class' => 'form-control hidden', 'value' => $d->_id]])->textInput() ?>
                                <?= $form->field($modelu, 'first_name', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control', 'value' => $d->first_name]])->textInput() ?>
                                <?= $form->field($modelu, 'last_name', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control', 'value' => $d->last_name]])->textInput() ?>
                                <?= $form->field($modelu, 'address', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control', 'value' => $d->address]])->textInput() ?>
                                <?= $form->field($modelu, 'email', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control', 'value' => $d->email]])->textInput() ?>
                                <?= $form->field($modelu, 'phone', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control', 'value' => $d->phone]])->textInput() ?>
                                <?= $form->field($modelu, 'user_role', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control', 'style' => 'padding:0px;', 'onChange' => 'getUsersList(\'' . $d->_id . '\')']])->dropDownList($roleList, ['prompt' => 'Select']) ?>
                                <?= $form->field($modelu, 'report_to', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control', 'style' => 'padding:0px;', 'value' => $d->report_to]])->dropDownList($userList, ['prompt' => 'Select']) ?>
                                <?= $form->field($modelu, 'password', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control', 'value' => ''], 'enableAjaxValidation' => true,])->passwordInput() ?>
                                <?= $form->field($modelu, 'confirm_password', ['options' => ['class' => 'divTableCell'], 'inputOptions' => ['class' => 'form-control'], 'enableAjaxValidation' => true,])->passwordInput() ?>
                                <?php
                                ActiveForm::end();
                            }
                            ?>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="noOrder">
                            <h1 style="text-align: center">No records found</h1>
                        </div>
                    <?php } ?>
                    <!--                    
                                        <div class="divTableRow">
                                            <div class="divTableCell first"><a href="#"><img src="images/edit_icon.png" class="save_icon"/></a></div>
                                            <div class="divTableCell"><span>1</span></div>
                                            <div class="divTableCell">Jeff</div>
                                            <div class="divTableCell">Alfred</div>
                                            <div class="divTableCell">6874 Stevenson Blvd, Fremont, CA</div>
                                            <div class="divTableCell">alfred@somend.com</div>
                                            <div class="divTableCell">212-247-7800</div>
                                            <div class="divTableCell">Sales Executive</div>
                                            <div class="divTableCell">Methew</div>
                                            <div class="divTableCell">  <input type="password" value="******" class="form-control" id="pwd"></div>
                                            <div class="divTableCell">  <input type="password" value="******" class="form-control" id="pwd"></div>
                                        </div>-->
                </div>

            </div>
            <!-- DivTable.com -->

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

