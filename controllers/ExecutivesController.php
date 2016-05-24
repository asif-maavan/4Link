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
//use app\common\models\user;
use yii\data\Pagination;
use yii\web\Controller;
use yii\widgets\ActiveForm;
use app\components\GlobalFunction;

/**
 * Description of ExecutivesController
 *
 * @author E-Teck Laptops
 */
class ExecutivesController extends Controller {

    const className = 'app\common\models\User';

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

        if (Yii::$app->request->get('sort')) {
            $sort = Yii::$app->request->get('sort');
        }
//        if (Yii::$app->request->get('nameS')) {
//            $nameS = Yii::$app->request->get('nameS');
//        }

        $whereParams = ['user_role' => \app\common\models\User::ROLE_executive];
        $count = GlobalFunction::getCount(['className' => $className, 'whereParams' => $whereParams, 'nameS' => $nameS]);
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => Yii::$app->params['pageSize']]);
        $data = GlobalFunction::getListing(['className' => $className, 'pagination' => $pagination, 'whereParams' => $whereParams, 'nameS' => $nameS, 'sort' => $sort, 'selectParams' => ['_id', 'user_id', 'profile_picture', 'first_name', 'last_name', 'email', 'phone', 'address', 'report_to', 'password', 'user_role', 'created']]);

        return $this->render('index', [
                    'data' => $data,
                    'pagination' => $pagination,
        ]);
    }

// end index
}

// end class
