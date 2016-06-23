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
class SalesController extends Controller {

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
                        'actions' => ['index', 'create', 'update', 'delete', 'validation', 'detail'],
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
        $model = new SalesForm();
        $model->scenario = 'create';
        $modelu = new SalesForm();
        $modelu->scenario = 'update';
        if (Yii::$app->request->post()) {                 // create Sale
            $cstmr = true;
            $model->load(Yii::$app->request->post());
            if ($model->customer_type == '1') {
                $customer = new \app\models\CustomerForm();
                $customer->scenario = 'createFromSale';
                $params['CustomerForm'] = ['first_name' => $model->customer_name, 'customer_acc' => $model->customer_acc_no, 'sales_agent' => $model->sale_executive];
                $customer->load($params);
                $ret = $customer->createOrUpdate($params['CustomerForm']);
                if ($ret['msgType'] == 'ERR') {
                    $cstmr = FALSE;
                    return json_encode($ret['msgArr']) . '<br>------------<br>' . json_encode($customer->attributes);
                } else {
                    $model->customer_name = $customer->_id;
                }
            }
            if ($cstmr) {
                $retData = $model->createOrUpdate(Yii::$app->request->post('SalesForm'));
                if ($retData['msgType'] == 'ERR') {
                    ;
                } else {
                    $model = new SalesForm();
                }
            }
        }

        if (Yii::$app->request->get('sort')) {
            $sort = Yii::$app->request->get('sort');
        }
        if (Yii::$app->request->get('status')) {
            $whereParams['order_state'] = Yii::$app->request->get('status');
        }
        if (Yii::$app->request->get('indexS')) {
            $whereParams['index_no'] = Yii::$app->request->get('indexS');
        }

        $count = GlobalFunction::getCount(['className' => $className, 'whereParams' => $whereParams, 'nameS' => $nameS]);
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => Yii::$app->params['pageSize']]);
        $data = GlobalFunction::getListing(['className' => $className, 'pagination' => $pagination, 'whereParams' => $whereParams, 'nameS' => $nameS, 'sort' => $sort, 'selectParams' => ['_id', 'uid', 'index_no', 'sale_executive', 'customer_type', 'order_type', 'customer_acc_no', 'customer_name', 'plan', 'siebel_activity_no', 'require_finance', 'require_account_transfer', 'sale_no', 'submitted_to_AT', 'order_state']]);

        return $this->render('index', [
                    'data' => $data,
                    'model' => $model,
                    'modelu' => $modelu,
                    'pagination' => $pagination,
                    'agentList' => GlobalFunction::getAgentList(),
                    'customerTypeList' => GlobalFunction::getCustomerTypes(),
                    'YN' => GlobalFunction::getYN(),
                    'orderTypeList' => GlobalFunction::getOrderTypeList(),
                    'customerList' => GlobalFunction::getCustomerList(),
                    'planList' => GlobalFunction::getPlansList(),
                    'orderStateList' => GlobalFunction::getOrderStateList(),
        ]);
    }

    public function actionValidation($s) {
        $model = new SalesForm();
        $model->scenario = $s;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {//echo json_encode($model->attributes);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    // update sale
    public function actionUpdate() {
        $model = new SalesForm();
        $model->scenario = 'update';
        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $retData = $model->createOrUpdate(Yii::$app->request->post('SalesForm'));

            if ($retData['msgType'] == 'ERR') {
                exit(json_encode(['msgType' => 'ERR', 'msgArr' => $retData['msgArr']]));
            } else {
                exit(json_encode(['msgType' => 'SUC']));
            }
        }
    }

    // sales detail
    public function actionDetail($id) {

        $sale = \app\common\models\Sales::findOne($id);
        if (count($sale) > 0) {
            $model = new SalesForm();
            $model->scenario = 'detail';
            $model->attributes = $sale->attributes;
            
            if (Yii::$app->request->post()) {                 // Detail Update
                $model->load(Yii::$app->request->post());
                $retData = $model->createOrUpdate(Yii::$app->request->post('SalesForm'));

                if ($retData['msgType'] == 'ERR') {
                    exit(json_encode(['msgType' => 'ERR', 'msgArr' => $retData['msgArr']]));
                } else {
                    $this->refresh(); //exit(json_encode(['msgType' => 'SUC']));
                }
            }
            
            $estData = GlobalFunction::getListing(['className' => 'app\common\models\Values', 'pagination' => '', 'whereParams' => '', 'nameS' => '', 'selectParams' => ['_id', 'name', 'value']]);
            $estList=[];
            foreach ($estData as $value) {
                $estList[$value->name] = $value->value;
            }

            return $this->render('detail', [
                        'model' => $model,
                        'countryList' => GlobalFunction::getCountries(),
                        'agentList' => GlobalFunction::getAgentList(),
                        'customerTypeList' => GlobalFunction::getCustomerTypes(),
                        'YN' => GlobalFunction::getYN(),
                        'orderTypeList' => GlobalFunction::getOrderTypeList(),
                        'customerList' => GlobalFunction::getCustomerList(),
                        'planList' => GlobalFunction::getPlansList(),
                        'planTypeList' => GlobalFunction::getPlanTypeList(),
                        'orderStateList' => GlobalFunction::getOrderStateList(),
                        'states' => GlobalFunction::getSalesDetailStatesList(),
                        'estList' => $estList,
            ]);
        } else {
            return $this->render('detail', [
                        'errmsg' => 'In valid Customer ID',
            ]);
        }
    }

// end class
}
