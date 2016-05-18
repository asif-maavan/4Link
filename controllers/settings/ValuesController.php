<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers\settings;

use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use app\common\models\User;
use app\models\AccountTypeForm;
use app\models\OrderTypeForm;
use yii\widgets\ActiveForm;
use app\components\GlobalFunction;

/**
 * Description of ValuesController
 *
 * @author Muhammad Asif Laptops
 */
class ValuesController extends Controller {

    public function actionIndex() {
        $classAType = 'app\common\models\AccountType';
        $classOType = 'app\common\models\OrderType';
        $sort = '';
        $AccModel = new AccountTypeForm();
        $AccModelu = new AccountTypeForm();
        $OrModel = new OrderTypeForm();
        $OrModelu = new OrderTypeForm();
        if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
//            if (Yii::$app->request->post()) {                 // create Plan
//                $model->load(Yii::$app->request->post());
//                $retData = $model->createOrUpdate();
//                if ($retData['msgType'] == 'ERR') {
//                    ;
//                } else {
//                    $model = new PlanForm();
//                }
//            }

//            if (Yii::$app->request->get()) {
//                $sort = Yii::$app->request->get('sort');
//            }
//            $AcCount = GlobalFunction::getCount(['className' => $className, 'whereParams' => '', 'nameS' => '']);
//            $pagination = new Pagination(['totalCount' => $count, 'pageSize' => Yii::$app->params['pageSize']]);
            $AcData = GlobalFunction::getListing(['className' => $classAType, 'pagination' => '', 'whereParams' => '', 'nameS' => '', 'sort' => $sort, 'selectParams' => ['_id', 'type_name']]);
            $OrData = GlobalFunction::getListing(['className' => $classOType, 'pagination' => '', 'whereParams' => '', 'nameS' => '', 'sort' => $sort, 'selectParams' => ['_id', 'type_name']]);
            return $this->render('index', [
                        'accountData' => $AcData,
                        'orderData' => $OrData,
                        'AccModel' => $AccModel,
                        'AccModelu' => $AccModelu,
                        'OrModel' => $OrModel,
                        'OrModelu' => $OrModelu,
                            //'pagination' => $pagination,
            ]);
        } else {
            $data = User::getListing(['whereParams' => ['email' => Yii::$app->user->identity->email]]);
            return $this->render('index', [
                        'data' => $data,
            ]);
        }
    }

// end function

    public function actionAccountType() {
        $model = new AccountTypeForm();
        //$model->scenario = 'update';
        if (Yii::$app->request->post()) {                 // create 
            $model->load(Yii::$app->request->post());
            $retData = $model->createOrUpdate();

            if ($retData['msgType'] == 'ERR') {
                exit(json_encode(['msgType' => 'ERR', 'msgArr' => $retData['msgArr']]));
            } else {
                if (Yii::$app->request->isAjax) {
                    exit(json_encode(['msgType' => 'SUC']));
                } else
                    $this->redirect(Yii::$app->urlManager->createUrl("settings/values/"));
                //exit(json_encode(['msgType' => 'SUC']));
            }
        }
    }

    public function actionDeleteAccountType() {
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            $id = Yii::$app->request->post('_id');
            $accType = \app\common\models\AccountType::findOne($id);
            $retData = $accType->delete();

            if ($retData['msgType'] == 'ERR') {
                exit(json_encode(['msgType' => 'ERR', 'msgArr' => $retData['msgArr']]));
            } else {
                exit(json_encode(['msgType' => 'SUC']));
            }
        }
    }

    public function actionOrderType() {
        $model = new OrderTypeForm();
        //$model->scenario = 'update';
        if (Yii::$app->request->post()) {                 // create 
            $model->load(Yii::$app->request->post());
            $retData = $model->createOrUpdate();

            if ($retData['msgType'] == 'ERR') {
                exit(json_encode(['msgType' => 'ERR', 'msgArr' => $retData['msgArr']]));
            } else {
                if (Yii::$app->request->isAjax) {
                    exit(json_encode(['msgType' => 'SUC']));
                } else
                    $this->redirect(Yii::$app->urlManager->createUrl("settings/values/"));
                //exit(json_encode(['msgType' => 'SUC']));
            }
        }
    }
    
    public function actionDeleteOrderType() {
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            $id = Yii::$app->request->post('_id');
            $accType = \app\common\models\OrderType::findOne($id);
            $retData = $accType->delete();

            if ($retData['msgType'] == 'ERR') {
                exit(json_encode(['msgType' => 'ERR', 'msgArr' => $retData['msgArr']]));
            } else {
                exit(json_encode(['msgType' => 'SUC']));
            }
        }
    }

}

// end class
