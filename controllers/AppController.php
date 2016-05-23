<?php
namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\components\AccessRule;
use yii\filters\VerbFilter;
use app\common\models\User;
use Yii;

/**
 * AppController extends Controller and implements the behaviors() method
 * where you can specify the access control ( AC filter + RBAC) for
 * your controllers and their actions.
 */
class AppController extends Controller
{
    /**
     * Returns a list of behaviors that this component should behave as.
     * Here we use RBAC in combination with AccessControl filter.
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                // We will override the default rule config with the new AccessRule class
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'controllers' => ['user'],
                        'actions' => ['my-account', 'update-validation'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'controllers' => ['user'],
                        //'actions' => ['create', 'delete', 'create-validation', 'update-validation'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_ADMIN
                        ],
                    ],
                    [
                        'controllers' => ['user'],
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'controllers' => ['ajax'],
                        //'actions' => ['rqst-info-email', 'user-info', 'update-payment', 'file-upload', 'send-itinerary', 'delete-itinerary', 'change-status', 's-m-order-payment', 's-m-user-list', 's-m-order-detail', 'use-s-m-miles'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

} // AppController
