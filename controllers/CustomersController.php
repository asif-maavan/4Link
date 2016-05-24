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
use app\common\models\Customer;
use yii\data\Pagination;
use yii\web\Controller;
use app\models\CustomerForm;
use yii\widgets\ActiveForm;
use app\components\GlobalFunction;

/**
 * Description of CustomerController
 *
 * @author Muhammad Asif
 */
class CustomersController extends Controller {

    const className = 'app\common\models\Customer';

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

// end function

    public function actionIndex() {
        $className = self::className;
        $nameS = $sort = '';
        $model = new CustomerForm();
        //$model->scenario = 'create';
        $modelu = new CustomerForm();
        //$modelu->scenario = 'update';
        if (Yii::$app->request->post()) {                 // create Plan
            $model->load(Yii::$app->request->post());
            $retData = $model->createOrUpdate(Yii::$app->request->post('CustomerForm'));
            if ($retData['msgType'] == 'ERR') {
                ;
            } else {
                $model = new CustomerForm();
            }
        }

        if (Yii::$app->request->get('sort')) {
            $sort = Yii::$app->request->get('sort');
        }
        if (Yii::$app->request->get('nameS')) {
            $nameS = Yii::$app->request->get('nameS');
        }

        $count = GlobalFunction::getCount(['className' => $className, 'whereParams' => '', 'nameS' => $nameS]);
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => Yii::$app->params['pageSize']]);
        $data = GlobalFunction::getListing(['className' => $className, 'pagination' => $pagination, 'whereParams' => '', 'nameS' => $nameS, 'sort' => $sort, 'selectParams' => ['_id', 'customer_id', 'customer_acc', 'first_name', 'account_no', 'address', 'phone', 'sales_agent', 'agent_phone']]);
        
        return $this->render('index', [
                    'data' => $data,
                    'model' => $model,
                    'modelu' => $modelu,
                    'pagination' => $pagination,
                    'agentList' => GlobalFunction::getAgentList(),
        ]);
    }

// end index
    
    public function actionUpdate() {
        $model = new CustomerForm();
        //$model->scenario = 'update';
        if (Yii::$app->request->post()) {                 // create Plan
            $model->load(Yii::$app->request->post());
            $retData = $model->createOrUpdate(Yii::$app->request->post('CustomerForm'));

            if ($retData['msgType'] == 'ERR') {
                exit(json_encode(['msgType' => 'ERR', 'msgArr' => $retData['msgArr']]));
            } else {
                exit(json_encode(['msgType' => 'SUC']));
            }
        }
    }
}

// end class
