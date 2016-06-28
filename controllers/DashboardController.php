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
        $whereParams = $indexS = $nameS = $sort = '';

        if (Yii::$app->request->get('from')) {
            $date1 = \DateTime::createFromFormat('d/m/Y', Yii::$app->request->get('from'))->format('Y-m-d h:i:s');            //exit();
            $date1 = new \MongoDate(strtotime($date1));
            $date2 = new \MongoDate(strtotime(date("Y-m-d 00:00:00")));
            $whereParams = ['between', 'created', $date1, $date2];
        }
        if (Yii::$app->request->get('manager')) {
            if (!empty($whereParams)) {
                $whereParams = ['and', $whereParams, ['team_leader._id' => Yii::$app->request->get('manager')]];
            } else
                $whereParams['team_leader._id'] = Yii::$app->request->get('manager');
        }
        if (Yii::$app->request->get('sale')) {
            $whereParams['sale_no'] = Yii::$app->request->get('sale');
        }
        
        if (Yii::$app->request->get('sort')) {
            $sort = Yii::$app->request->get('sort');
        }

        $count = GlobalFunction::getCount(['className' => $className, 'whereParams' => $whereParams, 'nameS' => $nameS]);
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => Yii::$app->params['pageSize']]);
        $data = GlobalFunction::getListing(['className' => $className, 'pagination' => '', 'whereParams' => $whereParams, 'nameS' => $nameS, 'sort' => $sort, 'selectParams' => ['_id', 'sale_executive', 'submitted_to_AT', 'order_state', 'total_MRC_per_order', 'total_FLP_per_order']]);
        $data2 = GlobalFunction::getListing(['className' => $className, 'pagination' => $pagination, 'whereParams' => $whereParams, 'nameS' => $nameS, 'sort' => $sort, 'selectParams' => ['_id', 'sale_no', 'created', 'submitted_to_AT', 'order_state']]);

        $graphData = [];
        array_push($graphData, ['Sales Executive', 'Total MRC', 'Total FLP']);
        $executives = [];
        //echo count($data);
        foreach ($data as $value) {
            $count = $mrc = $flp = 0;
            if (!isset($executives[$value->sale_executive['_id']])) {
                foreach ($data as $sale) {
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
                    'pagination' => $pagination,
                    'teamLeadList' => GlobalFunction::getTeamLeadList(),
                    'graphData' => $graphData,
        ]);
    }

// end class
}
