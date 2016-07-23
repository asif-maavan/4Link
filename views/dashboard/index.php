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

function getBarStats($model) {
    $verified = (!empty($model->created) && !empty($model->submitted)) ? \app\components\GlobalFunction::dateDiff(date('d/m/Y', $model->created->sec), $model->submitted) : 0;
    $finSubmitted = (!empty($model->date_require_fin) && !empty($model->submitted_to_finance)) ? \app\components\GlobalFunction::dateDiff(date('d/m/Y', $model->date_require_fin->sec), date('d/m/Y', $model->submitted_to_finance->sec)) : 0;
    $finApproved = (!empty($model->submitted_to_finance) && !empty($model->date_fin_approved)) ? \app\components\GlobalFunction::dateDiff(date('d/m/Y', $model->submitted_to_finance->sec), date('d/m/Y', $model->date_fin_approved->sec)) : 0;
    $atSubmitted = (!empty($model->date_require_at) && !empty($model->submitted_to_AT)) ? \app\components\GlobalFunction::dateDiff(date('d/m/Y', $model->date_require_at->sec), date('d/m/Y', $model->submitted_to_AT->sec)) : 0;
    $atApproved = (!empty($model->submitted_to_AT) && !empty($model->date_at_approved)) ? \app\components\GlobalFunction::dateDiff(date('d/m/Y', $model->submitted_to_AT->sec), date('d/m/Y', $model->date_at_approved->sec)) : 0;
    $soAssigned = 0;
    if (!empty($model->date_so_assigned)) {
        if (!empty($model->date_at_approved)) {
            $soAssigned = \app\components\GlobalFunction::dateDiff(date('d/m/Y', $model->date_at_approved->sec), date('d/m/Y', $model->date_so_assigned->sec));
        } elseif (!empty($model->date_fin_approved)) {
            $soAssigned = \app\components\GlobalFunction::dateDiff(date('d/m/Y', $model->date_fin_approved->sec), date('d/m/Y', $model->date_so_assigned->sec));
        } elseif (!empty($model->submitted)) {
            $soAssigned = \app\components\GlobalFunction::dateDiff(date('d/m/Y', $model->submitted), date('d/m/Y', $model->date_so_assigned->sec));
        }
    }
    $arc = (!empty($model->date_ARC) && !empty($model->date_so_assigned)) ? \app\components\GlobalFunction::dateDiff(date('d/m/Y', $model->date_so_assigned->sec), date('d/m/Y', $model->date_ARC->sec)) : 0;

    $stats = [1 => $verified, 2 => $finSubmitted, 3 => $finApproved, 4 => $atSubmitted, 5 => $atApproved, 6 => $soAssigned, 7 => $arc, 'total' => ($verified + $finSubmitted + $finApproved + $atSubmitted + $atApproved + $soAssigned + $arc)];
    return $stats;
}

function getMaxDays($data) {
    $max = 0;
    foreach ($data as $d) {
        $stats = getBarStats($d);
        if ($max < $stats['total'])
            $max = $stats['total'];
    }
    return $max;
}
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
    .bar{background-color: #d7f2ff;
         height: 19px;
         width: 100%;
         border: 2px solid;
         border-color: #c5c5c5;}
    .bar-head{
        width: 100%;
        color: #a6a6a6;
        font-size: smaller;
        margin: 0;
    }
    .bar-base{
        width: 100%;
        margin-bottom: 0;
        font-size: small;
        font-weight: 500;
        color: #222222;
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
                    <div class="col-lg-3 col-md-3 col-sm-4"><input type="text" name="from" class="form-control input_style dbg-date-img datepicker cal-img" id="usr" value="<?= Yii::$app->request->get('from') ? Yii::$app->request->get('from') : date('01/m/Y'); ?>"></div>
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
                            SO Number:
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-9 col-xs-7"><input type="text" name="sale" value="<?= !empty(Yii::$app->request->get('sale')) ? Yii::$app->request->get('sale') : '' ?>" class="form-control input_style" id="usr"></div>                 	
                </div>
                <button type="submit" class="btn-search hidden">Search</button>
                <!--</form>-->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-fix" style="overflow-x:auto;">
                            <table class="responsive_table responsive_table_bg">
                                <thead class="responsive_table_thead">
                                    <tr class="responsive_table_head">
                                        <th class="responsive_table_th">SO Number <a href="?sort=<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'so_no') ? 'so_no' : '-so_no' ?>"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-so_no' || Yii::$app->request->get('sort') != 'so_no') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></th>
                                        <th class="responsive_table_th">Date Created <a href="?sort=<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'created') ? 'created' : '-created' ?>"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-created' || Yii::$app->request->get('sort') != 'created') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></th>
                                        <th class="responsive_table_th">Value</th>
                                        <th class="responsive_table_th">Current State <a href="?sort=<?= (Yii::$app->request->get('sort')[0] == '-' || Yii::$app->request->get('sort') != 'order_state') ? 'order_state' : '-order_state' ?>"><img src="<?= $baseUrl ?>images/<?= (Yii::$app->request->get('sort') == '-order_state' || Yii::$app->request->get('sort') != 'order_state') ? 'down.png' : 'up.png' ?>" width="7" height="4" alt=""/></a></th>
                                        <th class="responsive_table_th">
                                            <div class="text-left">State Durations</div>
                                            <div class="th-desc">(1="Verified", 2="Submitted to FIN", 3="FIN Approved", 4="Submitted to AT", 5="AT Approved", 6="SO Assigned", 7="Activated/Rejected/Cancelled")</div>
                                        </th>
                                        <th class="responsive_table_th brn">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="responsive_table_tbody">
                                    <?php
                                    $max = getMaxDays($dataList);
                                    if (count($dataList) > 0) {
                                        foreach ($dataList as $d) {
                                            $stats = getBarStats($d);
                                            ?>
                                            <tr class="responsive_table_tr">
                                                <td class="responsive_table_td">
                                                    <div class="responsive_table_title_column">Framework</div>
                                                    <div class="responsive_table_value"><?= $d->so_no ?></div>
                                                </td>
                                                <td class="responsive_table_td">
                                                    <div class="responsive_table_title_column">Twitter Bootstrap</div>
                                                    <div class="responsive_table_value"><?= date('M d, Y', $d->created->sec) ?></div>
                                                </td>
                                                <td class="responsive_table_td">
                                                    <div class="responsive_table_title_column">Foundation</div>
                                                    <div class="responsive_table_value"><?= $d->QTY ? '$' . $d->QTY * $d->four_link_points : '-' ?></div>
                                                </td>
                                                <td class="responsive_table_td">
                                                    <div class="responsive_table_title_column">Semantic-ui</div>
                                                    <div class="responsive_table_value"><?= $d->order_state ?></div>
                                                </td>
                                                <td class="responsive_table_td">
                                                    <div class="responsive_table_title_column">Metro UI</div>
                                                    <!--<div class="responsive_table_value"><img src="<?= $baseUrl ?>images/b1.png" /></div>-->
                                                    <div class="responsive_table_value">
                                                        <div style="width:91%;height:auto;margin:auto;background-color: #d7ffff;">
                                                            <div style="width:auto;height:auto;margin:auto;">
                                                                <?php
                                                                $first = $last = '';
                                                                $count = 0;
                                                                foreach ($stats as $key => $value) {
                                                                    if ($value > 0 && $key != 'total') {
                                                                        $last = $key;
                                                                        $count++;
                                                                        if ($count == 1) {
                                                                            $first = $key;
                                                                        }
                                                                    }
                                                                }
                                                                foreach ($stats as $key => $value) {
                                                                    if ($value > 0 && $key != 'total') {
                                                                        //echo $first . '-' . $last;
                                                                        $late = ($estValues[$key] < $value) ? TRUE : FALSE;
                                                                        $bRadius = ($key == 1) ? 'border-top-left-radius: 10px;border-bottom-left-radius: 10px;' : '';
                                                                        $bRadius = ($key == $last) ? $bRadius . 'border-top-right-radius: 10px;border-bottom-right-radius: 10px;' : $bRadius;
                                                                        if ($key == $first && $key == $last)
                                                                            $border = '';
                                                                        elseif ($key == $first)
                                                                            $border = 'border-right-width: 1px;';
                                                                        elseif ($key == $last) {
                                                                            $border = 'border-left-width: 1px;';
                                                                        } else
                                                                            $border = 'border-left-width: 1px;border-right-width: 1px;';

                                                                        $borderColor = ($late) ? 'border-color: #ff0000;' : 'border-color: #c5c5c5;';
                                                                        $color = ($late) ? 'background-color: #ffbcbc;' : 'background-color: #d7f2ff;';
                                                                        $with = (($value / ($max)) * (100));
                                                                        ?>
                                                                        <div style="width:<?= $with . '%' ?>;float: left; margin:auto;">
                                                                            <p class="text-center bar-head" style=""><?= $key ?></p>
                                                                            <div class="bar" style="background-color: #d7f2ff;<?= $bRadius . $border . $borderColor . $color ?>" ></div>
                                                                            <p class="text-center bar-base" style=""><?= $value ?></p>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="responsive_table_td brn">
                                                    <div class="responsive_table_title_column">Kube</div>
                                                    <div class="responsive_table_value"><span><?= $stats['total'] . 'd' ?></span></div>
                                                </td>
                                            </tr>

                                            <?php
                                        }
                                    }
                                    ?>

<!--                                    <tr class="responsive_table_tr">
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
                                    </tr>-->

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