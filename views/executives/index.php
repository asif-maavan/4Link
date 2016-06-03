<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use \app\common\models\User;
use yii\widgets\ActiveForm;
use app\models\Categories;

$this->title = 'Sales Executives';
$this->params['breadcrumbs'][] = $this->title;
//$this->registerJsFile('@web/js/customer.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
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
            <li role="presentation" class="active border nav-tabs-wraper_selected"><a href="<?= Yii::$app->urlManager->createUrl("executives/"); ?>">Sales Executives</a></li>
            <li role="presentation"><a href="<?= Yii::$app->urlManager->createUrl("customers/"); ?>">Customers</a></li>              
        </ul>
        <div class="col-md-12 content_wraper2">

            <div class="content-box-large app-content-box-large" style="height: auto;">

<!--                <div class="row cstname">
                    <form class="form-inline" method="get">
                        <input type="text" class="cstname-txtbx2 cstname-img" name="nameS" placeholder="Customer Name" value="<?php echo Yii::$app->request->get('nameS'); ?>" >
                    </form>
                </div>-->
                <div class="divTable">
                    <div class="divTableBody">
                        <div class="divTableRow">
                            <div class="divTableCell th_bg row_1 first">Picture</div>
                            <div class="divTableCell th_bg row_2 text-center">UID<a href="?sort=<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'user_id') ? 'user_id' : '-user_id' ?>"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-user_id' || Yii::$app->request->get('sort') != 'user_id') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a>&nbsp;</div>  <!-- <img src="<?= $baseUrl ?>images/down.png" width="7" height="4" alt=""/> -->
                            <div class="divTableCell th_bg row_3 text-center">Name<a href="?sort=<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'first_name') ? 'first_name' : '-first_name' ?>"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-first_name' || Yii::$app->request->get('sort') != 'first_name') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></div>
                            <div class="divTableCell th_bg row_4 text-center">Delayed<a href="?sort=<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'first_name') ? 'first_name' : '-first_name' ?>"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-first_name' || Yii::$app->request->get('sort') != 'first_name') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></div>
                            <div class="divTableCell th_bg row_5 text-center">Manager<a href="?sort=<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'account_no') ? 'account_no' : '-account_no' ?>"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-account_no' || Yii::$app->request->get('sort') != 'account_no') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></div>
                            <div class="divTableCell th_bg row_date text-center">Start Date</div>
                            <div class="divTableCell th_bg row_date text-center">Last Sale Date</div>
                            <div class="divTableCell th_bg row_8 text-center">Total MRC</div>
                            <div class="divTableCell th_bg row_9 text-center">Average MRC</div>
                            <div class="divTableCell th_bg row_8 text-center">Total FLP</div>
                            <div class="divTableCell th_bg row_9 text-center">Average FLP</div>
                        </div>
                        <!-- records -->
                        <?php
                        if (count($data) > 0) {
                            foreach ($data as $d) {
                                ?>
                                <div id="<?= $d->_id . 'D' ?>" class="divTableRow data" > 
                                    <div class="divTableCell cel-padding first vertical-align"><div class="profile-div"><img class="img-responsive" src="<?= $baseUrl ?><?=($d->profile_picture)? 'uploads/'.$d->profile_picture: 'images/user-img1.png' ?>" class="save_icon"/></div></div>
                                    <div class="divTableCell cel-padding text-center vertical-align">E<?= $d->user_id ?></div>
                                    <div id="customer_acc" class="divTableCell cel-padding text-center vertical-align"><?= $d->first_name.' '.$d->last_name ?></div>
                                    <div id="first_name" class="divTableCell cel-padding text-center vertical-align"><?= 9 ?></div>
                                    <div id="account_no" class="divTableCell cel-padding text-center vertical-align"><?= (!is_array($d->report_to)) ? $d->report_to : $d->report_to['name'] ?></div>
                                    <div id="address" class="divTableCell cel-padding text-center vertical-align"><?= date('d-M-Y', strtotime($d->created)) ?></div>
                                    <div id="phone" class="divTableCell cel-padding text-center vertical-align"><?= date('d-M-Y', strtotime($d->created)) ?></div>
                                    <div id="sales_agent" class="divTableCell cel-padding text-center vertical-align"><?= 44 ?></div>
                                    <div id="agent_phone" class="divTableCell cel-padding text-center vertical-align"><?= 4 ?></div>
                                    <div id="agent_phone" class="divTableCell cel-padding text-center vertical-align"><?= 55 ?></div>
                                    <div id="agent_phone" class="divTableCell cel-padding text-center vertical-align"><?= 5 ?></div>
                                </div>
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