<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use \app\common\models\User;
use yii\widgets\ActiveForm;
use app\models\Categories;

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('https://www.gstatic.com/charts/loader.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/dashboard.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$baseUrl = Yii::$app->request->baseUrl . '/';
?>
<!--<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>-->
<script>
    var graphData = <?= json_encode($graphData) ?>;
</script>
<style>
    .form-control {
        margin: 0 !important;
    }
    .row{
        margin-left: 0;
        margin-right: 0;
    }
    .responsive_table_th img {
        padding-left: 0px !important; 
    }
</style>

<div class=" row ">
    <div class="row mg-top-o">
        <div class="row">
            <ul class="col-sm-12 nav nav-tabs nav-tabs-wraper">
                <li role="presentation" class="active border nav-tabs-wraper_selected"><a href="<?= Yii::$app->urlManager->createUrl("dashboard/"); ?>">Dashboard</a></li>
                <li role="presentation"><a href="<?= Yii::$app->urlManager->createUrl("sales/"); ?>">Sales</a></li>
                <li role="presentation"><a href="<?= Yii::$app->urlManager->createUrl("executives/"); ?>">Sales Executives</a></li>
                <li role="presentation"><a href="<?= Yii::$app->urlManager->createUrl("customers/"); ?>">Customers</a></li>              
            </ul></div>
        <div class="col-md-12 content_wraper2">

            <!--<div class="content-box-large app-content-box-large" style="height: auto;">-->
            <form  class="content-box-large app-content-box-large" style="height: auto;" action="<?php echo Yii::$app->urlManager->createUrl("dashboard"); ?>" method="get" role="form">
                <div class="row">
                    <div class="col-lg-1 col-md-1 col-sm-1">
                        <div class="labelfix">                                                          
                            From:
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4"><input type="text" name="from" class="form-control input_style dbg-date-img datepicker cal-img" id="usr" value="<?php echo Yii::$app->request->get('from'); ?>"></div>
                    <div class="col-lg-5 col-md-5 col-sm-3">
                        <div class="labelfix text-right">
                            Manager/Supervisor:
                        </div>                                
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4">
                        <select name="manager" class="form-control input_style" id="sel2"> 
                            <option value="">All</option> 
                            <?php foreach ($teamLeadList as $key => $value) { ?>
                                <option value="<?= $key ?>" <?= (Yii::$app->request->get('manager') == $key) ? 'selected' : '' ?>><?= $value ?></option> 
                            <?php } ?>
                        </select>
                    </div>                    
                </div>
                <div class="row">
                    <div class="row">
                        <div class="col-md-12 dbg-graph">
                            <!--<img src="<?= $baseUrl ?>images/graph.jpg" />-->
                            <div id="chart_div" style="min-height: 401px;width: 80%;margin: auto;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 dbg-center">
                            <div class="dbg-bluebar">Total MRC: <span id="mrc"></span></div>
                            <div class="dbg-grayebar">Total FLP: <span id="flp"></span></div>                        
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="dbg-info"><span></span>Sales</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-5">
                        <div class="labelfix">
                            Sale Number:
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-9 col-xs-7"><input type="text" name="sale" class="form-control input_style" id="usr"></div>                 	
                </div>
                <button type="submit" class="btn-search hidden">Search</button>
                <!--</form>-->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-fix" style="overflow-x:auto;">
                            <table class="responsive_table responsive_table_bg">
                                <thead class="responsive_table_thead">
                                    <tr class="responsive_table_head">
                                        <th class="responsive_table_th">Sale Number <a href="?sort=<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'sale_no') ? 'sale_no' : '-sale_no' ?>"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-sale_no' || Yii::$app->request->get('sort') != 'sale_no') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></th>
                                        <th class="responsive_table_th">Date Created <a href="?sort=<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'created') ? 'created' : '-created' ?>"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-created' || Yii::$app->request->get('sort') != 'created') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></th>
                                        <th class="responsive_table_th">Value</th>
                                        <th class="responsive_table_th">Current State <a href="?sort=<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'order_state') ? 'order_state' : '-order_state' ?>"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-order_state' || Yii::$app->request->get('sort') != 'order_state') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></th>
                                        <th class="responsive_table_th">
                                            <div class="text-left">State Durations</div>
                                            <div class="th-desc">(1="Created", 2="Verified", 3="Finance Approval", 4="Finance Approved", 5="Account Transfer Approved", 6="Out for Delivery Activated/Rejected/Cancelled")</div>
                                        </th>
                                        <th class="responsive_table_th brn">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="responsive_table_tbody">
                                    <?php
                                    if (count($dataList) > 0) {
                                        foreach ($dataList as $d) {
                                            ?>
                                            <tr class="responsive_table_tr">
                                                <td class="responsive_table_td">
                                                    <div class="responsive_table_title_column">Framework</div>
                                                    <div class="responsive_table_value"><?= $d->sale_no ?></div>
                                                </td>
                                                <td class="responsive_table_td">
                                                    <div class="responsive_table_title_column">Twitter Bootstrap</div>
                                                    <div class="responsive_table_value"><?= date('M d, Y', $d->created->sec) ?></div>
                                                </td>
                                                <td class="responsive_table_td">
                                                    <div class="responsive_table_title_column">Foundation</div>
                                                    <div class="responsive_table_value">$7,295</div>
                                                </td>
                                                <td class="responsive_table_td">
                                                    <div class="responsive_table_title_column">Semantic-ui</div>
                                                    <div class="responsive_table_value"><?= $d->order_state ?></div>
                                                </td>
                                                <td class="responsive_table_td">
                                                    <div class="responsive_table_title_column">Metro UI</div>
                                                    <div class="responsive_table_value"><img src="<?= $baseUrl ?>images/b1.png" /></div>
                                                </td>
                                                <td class="responsive_table_td brn">
                                                    <div class="responsive_table_title_column">Kube</div>
                                                    <div class="responsive_table_value"><span>15d</span></div>
                                                </td>
                                            </tr>

                                            <?php
                                        }
                                    }
                                    ?>

                                    <tr class="responsive_table_tr">
                                        <td class="responsive_table_td">
                                            <div class="responsive_table_title_column">Framework</div>
                                            <div class="responsive_table_value">42</div>
                                        </td>
                                        <td class="responsive_table_td">
                                            <div class="responsive_table_title_column">Twitter Bootstrap</div>
                                            <div class="responsive_table_value">Sep 30, 2015

                                            </div>
                                        </td>
                                        <td class="responsive_table_td">
                                            <div class="responsive_table_title_column">Foundation</div>
                                            <div class="responsive_table_value">$11,458

                                            </div>
                                        </td>
                                        <td class="responsive_table_td">
                                            <div class="responsive_table_title_column">Semantic-ui</div>
                                            <div class="responsive_table_value">Verified</div>
                                        </td>
                                        <td class="responsive_table_td">
                                            <div class="responsive_table_title_column">Metro UI</div>
                                            <div class="responsive_table_value"><img src="<?= $baseUrl ?>images/b2.png" /></div>
                                        </td>
                                        <td class="responsive_table_td brn">
                                            <div class="responsive_table_title_column">Kube</div>
                                            <div class="responsive_table_value"><span>15d</span></div>
                                        </td>
                                    </tr>
                                    <tr class="responsive_table_tr">
                                        <td class="responsive_table_td">
                                            <div class="responsive_table_title_column">Framework</div>
                                            <div class="responsive_table_value">56</div>
                                        </td>
                                        <td class="responsive_table_td">
                                            <div class="responsive_table_title_column">Twitter Bootstrap</div>
                                            <div class="responsive_table_value">Jan 15, 2016</div>
                                        </td>
                                        <td class="responsive_table_td">
                                            <div class="responsive_table_title_column">Foundation</div>
                                            <div class="responsive_table_value"><p>$15,590</p>
                                            </div>
                                        </td>
                                        <td class="responsive_table_td">
                                            <div class="responsive_table_title_column">Semantic-ui</div>
                                            <div class="responsive_table_value">Finance Approval</div>
                                        </td>
                                        <td class="responsive_table_td">
                                            <div class="responsive_table_title_column">Metro UI</div>
                                            <div class="responsive_table_value"><img src="<?= $baseUrl ?>images/b3.png" /></div>
                                        </td>
                                        <td class="responsive_table_td brn">
                                            <div class="responsive_table_title_column">Kube</div>
                                            <div class="responsive_table_value"><span>15d</span></div>
                                        </td>
                                    </tr>
                                    <tr class="responsive_table_tr">
                                        <td class="responsive_table_td">
                                            <div class="responsive_table_title_column">Framework</div>
                                            <div class="responsive_table_value">11</div>
                                        </td>
                                        <td class="responsive_table_td">
                                            <div class="responsive_table_title_column">Twitter Bootstrap</div>
                                            <div class="responsive_table_value">Mar 16, 2016

                                            </div>
                                        </td>
                                        <td class="responsive_table_td">
                                            <div class="responsive_table_title_column">Foundation</div>
                                            <div class="responsive_table_value">$20,700</div>
                                        </td>
                                        <td class="responsive_table_td">
                                            <div class="responsive_table_title_column">Semantic-ui</div>
                                            <div class="responsive_table_value">Finance Approved</div>
                                        </td>
                                        <td class="responsive_table_td">
                                            <div class="responsive_table_title_column">Metro UI</div>
                                            <div class="responsive_table_value"><img src="<?= $baseUrl ?>images/b4.png" /></div>
                                        </td>
                                        <td class="responsive_table_td brn">
                                            <div class="responsive_table_title_column">Kube</div>
                                            <div class="responsive_table_value"><span>15d</span></div>
                                        </td>
                                    </tr>
                                    <tr class="responsive_table_tr">
                                        <td class="responsive_table_td">
                                            <div class="responsive_table_title_column">Framework</div>
                                            <div class="responsive_table_value">24</div>
                                        </td>
                                        <td class="responsive_table_td">
                                            <div class="responsive_table_title_column">Twitter Bootstrap</div>
                                            <div class="responsive_table_value">Apr 22, 2016</div>
                                        </td>
                                        <td class="responsive_table_td">
                                            <div class="responsive_table_title_column">Foundation</div>
                                            <div class="responsive_table_value">22,800</div>
                                        </td>
                                        <td class="responsive_table_td">
                                            <div class="responsive_table_title_column">Semantic-ui</div>
                                            <div class="responsive_table_value">Account Transfer Approved</div>
                                        </td>
                                        <td class="responsive_table_td">
                                            <div class="responsive_table_title_column">Metro UI</div>
                                            <div class="responsive_table_value"><img src="<?= $baseUrl ?>images/b5.png" /></div>
                                        </td>
                                        <td class="responsive_table_td brn">
                                            <div class="responsive_table_title_column">Kube</div>
                                            <div class="responsive_table_value"><span>15d</span></div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- pagination div-->
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
                <!--</div>-->
            </form>

        </div>

    </div>

</div>