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
            if($executive){
                exit(json_encode(['msgType' => 'SUC', 'phone'=> $executive->phone]));
            }
            
        }
    }
    
    public function actionCustomer() {
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id');
            $customer = \app\common\models\Customer::findOne($id);
            if($customer){
                exit(json_encode(['msgType' => 'SUC', 'acc'=> $customer->customer_acc]));
            }
            
        }
    }

}
