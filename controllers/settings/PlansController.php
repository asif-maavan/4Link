<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlansController
 *
 * @author Eagle Laptops
 */

namespace app\controllers\settings;

use Yii;
use app\common\models\User;
use yii\data\Pagination;
use yii\web\Controller;
use app\models\PlanForm;
use yii\widgets\ActiveForm;
use app\components\GlobalFunction;

class PlansController extends Controller {

    const className = 'app\common\models\Plans';

    public function actionIndex() {
        $className = self::className; //echo $className; exit();
        $model = new PlanForm();
        //$model->scenario = 'create';
        $modelu = new PlanForm();
        //$modelu->scenario = 'update';
        if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            if (Yii::$app->request->post()) {                 // create Plan
                $model->load(Yii::$app->request->post());
                $retData = $model->createOrUpdate();
                if ($retData['msgType'] == 'ERR') {
                    echo 'hi...'
                    ;
                } else {
                    $model = new PlanForm();
                }
            }
//            $count = User::getCount();
//            $pagination = new Pagination(['totalCount' => $count, 'pageSize' => Yii::$app->params['pageSize']]);
//            $data = User::getListing(['pagination' => $pagination]);
            $count = GlobalFunction::getCount(['className' => $className, 'whereParams' => '', 'nameS' => '']);
            $pagination = new Pagination(['totalCount' => $count, 'pageSize' => Yii::$app->params['pageSize']]);
            $data = GlobalFunction::getListing(['className' => $className, 'pagination' => $pagination, 'whereParams' => '', 'nameS' => '', 'sort' => '', 'selectParams' => ['_id', 'name', 'plan_group', 'plan_type', 'contract_period', 'mrc', 'fourlink_points']]);

            return $this->render('index', [
                        'data' => $data,
                        'model' => $model,
                        'modelu' => $modelu,
                        'pagination' => $pagination,
            ]);
        } else {
            $data = User::getListing(['whereParams' => ['email' => Yii::$app->user->identity->email]]);
            return $this->render('index', [
                        'data' => $data,
            ]);
        }
    }

    public function actionUpdate() {
        $model = new PlanForm();
        //$model->scenario = 'update';
        if (Yii::$app->request->post()) {                 // create Plan
            $model->load(Yii::$app->request->post());
            $retData = $model->createOrUpdate();

            if ($retData['msgType'] == 'ERR') {
                exit(json_encode(['msgType' => 'ERR', 'msgArr' => $retData['msgArr']]));
            } else {
                exit(json_encode(['msgType' => 'SUC']));
            }
        }
    }
    
    public function actionDelete() {
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) { 
            $id = Yii::$app->request->post('_id');
            $plan = \app\common\models\Plans::findOne($id);
            $retData = $plan->delete();
            
            if ($retData['msgType'] == 'ERR') {
                exit(json_encode(['msgType' => 'ERR', 'msgArr' => $retData['msgArr']]));
            } else {
                exit(json_encode(['msgType' => 'SUC']));
            }
        }
    }

}

// end class
