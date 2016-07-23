<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\components\AccessRule;
//use app\common\models\Customer;
use yii\data\Pagination;
use yii\web\Controller;
use app\models\SalesForm;
use yii\widgets\ActiveForm;
use app\components\GlobalFunction;

/**
 * Description of SalesController
 *
 * @author Muhammad Asif
 */
class DashboardController extends Controller {

    const className = 'app\common\models\Sales';

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    // index function for listing
    public function actionIndex() {
        $className = self::className;
        $classValues = 'app\common\models\Values';
        $whereParams = $indexS = $nameS = $sort = '';

        if (Yii::$app->request->get('from')) {
            $date1 = \DateTime::createFromFormat('d/m/Y', Yii::$app->request->get('from'))->format('Y-m-d 00:00:00');            //exit();
            $date1 = new \MongoDate(strtotime($date1));
            $date2 = new \MongoDate(strtotime(date("Y-m-d 00:00:00")));
            $whereParams = ['between', 'created', $date1, $date2];
        } else {
            $date1 = new \MongoDate(strtotime(date("Y-m-01 00:00:00")));
            $date2 = new \MongoDate(strtotime(date("Y-m-d 00:00:00")));
            $whereParams = ['between', 'created', $date1, $date2];
        }
        if (Yii::$app->request->get('sale')) {
            //$whereParams['so_no'] = Yii::$app->request->get('sale');
            $whereParams = ['and', $whereParams, ['so_no' => Yii::$app->request->get('sale')]];
        }
        if (Yii::$app->request->get('manager')) {
            if (!empty($whereParams)) {
                $whereParams = ['and', $whereParams, ['team_leader._id' => Yii::$app->request->get('manager')]];
            } else
                $whereParams['team_leader._id'] = Yii::$app->request->get('manager');
        }

        if (Yii::$app->request->get('sort')) {
            $sort = Yii::$app->request->get('sort');
        }

        $count = GlobalFunction::getCount(['className' => $className, 'whereParams' => $whereParams, 'nameS' => $nameS]);
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => Yii::$app->params['pageSize']]);
        $list = $data = GlobalFunction::getListing(['className' => $className, 'pagination' => '', 'whereParams' => $whereParams, 'nameS' => $nameS, 'sort' => $sort, 'selectParams' => ['_id', 'sale_executive', 'submitted_to_AT', 'order_state', 'total_MRC_per_order', 'total_FLP_per_order']]);
        $data2 = GlobalFunction::getListing(['className' => $className, 'pagination' => $pagination, 'whereParams' => $whereParams, 'nameS' => $nameS, 'sort' => $sort, 'selectParams' => ['_id', 'so_no', 'created', 'order_state', 'QTY', 'four_link_points', 'submitted', 'date_require_fin', 'submitted_to_finance', 'date_fin_approved', 'date_require_at', 'submitted_to_AT', 'date_at_approved', 'date_so_assigned', 'date_ARC']]);
        $VData = GlobalFunction::getListing(['className' => $classValues, 'pagination' => '', 'whereParams' => '', 'nameS' => '', 'sort' => $sort, 'selectParams' => ['_id', 'name', 'value']]);
        $estValues = [];
        foreach ($VData as $v) {
            if ($v->name == 'Verified') {
                $estValues[1] = $v->value;
            } elseif ($v->name == 'Submitted to FIN') {
                $estValues[2] = $v->value;
            } elseif ($v->name == 'FIN Approved') {
                $estValues[3] = $v->value;
            } elseif ($v->name == 'Submitted to AT') {
                $estValues[4] = $v->value;
            } elseif ($v->name == 'AT Approved') {
                $estValues[5] = $v->value;
            } elseif ($v->name == 'SO Assigned') {
                $estValues[6] = $v->value;
            } elseif ($v->name == 'ARC') {
                $estValues[7] = $v->value;
            }
        }

        $graphData = [];
        array_push($graphData, ['Sales Executive', 'Total MRC', 'Total FLP']);
        $executives = [];
        //echo count($data);
        foreach ($data as $value) {
            $count = $mrc = $flp = 0;
            if (!isset($executives[$value->sale_executive['_id']])) {
                foreach ($list as $sale) {
                    if ($value->sale_executive['_id'] == $sale->sale_executive['_id']) {
                        $mrc+= $sale->total_MRC_per_order;
                        $flp+= $sale->total_FLP_per_order;
                    }
                }
                array_push($graphData, [$value->sale_executive['name'], $mrc, $flp]);
            }
            $executives[$value->sale_executive['_id']] = 'true';
        }

        return $this->render('index', [
                    'data' => $data,
                    'dataList' => $data2,
                    'estValues' => $estValues,
                    'pagination' => $pagination,
                    'teamLeadList' => GlobalFunction::getTeamLeadList(),
                    'graphData' => $graphData,
        ]);
    }

// end class
}
