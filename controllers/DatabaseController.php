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
class DatabaseController extends Controller {

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
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    // index function for listing
    public function actionIndex() {
        
    }

    public function actionDelete() {

        if (Yii::$app->request->get('collection')) {
            $db = \app\common\models\Sales::getDb();
            $collection = Yii::$app->request->get('collection');
            if (Yii::$app->request->get('field') && Yii::$app->request->get('value')) {
                $where[Yii::$app->request->get('field')] = Yii::$app->request->get('value');echo json_encode($where);
                $result = $db->getCollection($collection)->remove($where);
            } else
                $result = $db->getCollection($collection)->remove();

            echo '<div >' . json_encode($result) . '</div>';
        }
    }

// end class
}
