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
                exit(json_encode(['msgType' => 'SUC', 'phone' => $executive->phone, 'agent'=>['report_to'=> $executive->report_to['name']]]));
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

//end class
}
