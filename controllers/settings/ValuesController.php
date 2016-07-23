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
        $classValues = 'app\common\models\Values';
        $sort = '';
        $AccModel = new AccountTypeForm();
        $AccModelu = new AccountTypeForm();
        $OrModel = new OrderTypeForm();
        $OrModelu = new OrderTypeForm();
        $valuesModel = new \app\models\ValuesForm();
        if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {

            $AcData = GlobalFunction::getListing(['className' => $classAType, 'pagination' => '', 'whereParams' => '', 'nameS' => '', 'sort' => $sort, 'selectParams' => ['_id', 'type_name']]);
            $OrData = GlobalFunction::getListing(['className' => $classOType, 'pagination' => '', 'whereParams' => '', 'nameS' => '', 'sort' => $sort, 'selectParams' => ['_id', 'type_name']]);
            $VData = GlobalFunction::getListing(['className' => $classValues, 'pagination' => '', 'whereParams' => '', 'nameS' => '', 'sort' => $sort, 'selectParams' => ['_id', 'name', 'value']]);
            return $this->render('index', [
                        'accountData' => $AcData,
                        'orderData' => $OrData,
                        'VData' => $VData,
                        'AccModel' => $AccModel,
                        'AccModelu' => $AccModelu,
                        'OrModel' => $OrModel,
                        'OrModelu' => $OrModelu,
                        'valuesModel' => $valuesModel,
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
                    $this->redirect(Yii::$app->urlManager->createUrl("settings/values#at/"));
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
                    $this->redirect(Yii::$app->urlManager->createUrl("settings/values#ot/"));
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

    // values
    public function actionValue() {
        if (Yii::$app->request->post()) {
            //$id = Yii::$app->request->post('_id');
            $model = new \app\models\ValuesForm();
            $model->load(Yii::$app->request->post());
            $name = $model->name;
            if ($name == 'Verified' || $name == 'Submitted to FIN' || $name == 'FIN Approved' || $name == 'Submitted to AT' || $name == 'AT Approved' || $name == 'SO Assigned'  || $name == 'ARC') {
                
                $retData = $model->createOrUpdate();
                if ($retData['msgType'] == 'ERR') {
                    exit(json_encode(['msgType' => 'ERR', 'msgArr' => $retData['msgArr']]));
                } else {
                    return $this->redirect(Yii::$app->urlManager->createUrl("settings/values#est/"));
                    //exit(json_encode(['msgType' => 'SUC']));
                }
            }  else {
                return $this->redirect(Yii::$app->urlManager->createUrl("settings/values/"));
            }
        }
    }

// end class
}
