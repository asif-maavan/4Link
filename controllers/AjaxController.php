<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\components\GlobalFunction;
use yii\web\UploadedFile;
use yii\helpers\Url;
use mPDF;

class AjaxController extends AppController {

    public function actionSalesExecutive() {
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id');
            $executive = \app\common\models\User::findOne($id);
            if ($executive) {
                exit(json_encode(['msgType' => 'SUC', 'phone' => $executive->phone, 'agent' => ['report_to' => $executive->report_to['name']]]));
            }
        }
    }

    public function actionCustomer() {
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id');
            $customer = \app\common\models\Customer::findOne($id);
            if ($customer) {
                exit(json_encode(['msgType' => 'SUC', 'acc' => $customer->customer_acc]));
            }
        }
    }

    public function actionPlan() {
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id');
            $plan = \app\common\models\Plans::findOne($id);
            if ($plan) {
                $arr = ['name' => $plan->name,
                    'plan_group' => $plan->plan_group,
                    'plan_type' => GlobalFunction::getPlanTypeList()[$plan->plan_type],
                    'contract_period' => $plan->contract_period,
                    'mrc' => $plan->mrc,
                    'contract_period' => $plan->contract_period,
                    'fourlink_points' => $plan->fourlink_points];
                exit(json_encode(['msgType' => 'SUC', 'plan' => $arr]));
            }
        }
    }

    public function actionSalesSubmitTo() {
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id');
            $submitTo = Yii::$app->request->post('submitTo');
            $sale = \app\common\models\Sales::findOne($id);
            if ($sale) {
                if ($submitTo == 'finance') {
                    $sale->submitted_to_finance = new \MongoDate ();
                    $sale->order_state = 'Submitted to FIN';
                } elseif ($submitTo == 'AT') {
                    $sale->submitted_to_AT = new \MongoDate ();
                    $sale->order_state = 'Submitted to AT';
                } elseif ($submitTo == 'LD') {
                    $sale->submitted_to_LD = new \MongoDate ();
                } elseif ($submitTo == 'RG') {
                    $sale->submitted_to_RG = new \MongoDate ();
                }

                $result = $sale->save();
                if ($result)
                    exit(json_encode(['msgType' => 'SUC', 'result' => $result, 'status'=> $sale->order_state, 'date'=>date('d/m/Y')]));
                else {
                    exit(json_encode(['msgType' => 'ERR', 'result' => $sale->errors]));
                }
            }
        }
    }

    public function actionSalesSoAssigned() {
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id');
            $SO = Yii::$app->request->post('so');
            $sale = \app\common\models\Sales::findOne($id);
            if ($sale) {
                $status = '';
                $sale->so_no = $SO;
                if (!empty($sale->so_no)) {
                    $sale->date_so_assigned = new \MongoDate ();
                    $status = $sale->order_state = 'SO Assigned';
                } else {
                    $sale->date_so_assigned = NULL;
                }

                $result = $sale->save();
                if ($result) {
                    exit(json_encode(['msgType' => 'SUC', 'result' => $result, 'status' => $status]));
                } else {
                    exit(json_encode(['msgType' => 'ERR', 'result' => $sale->errors]));
                }
            }
        }
    }

//end class
}
