<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\common\models;

use Yii;
use yii\mongodb\ActiveRecord;
use yii\behaviors\AttributeBehavior;

/**
 * Description of Customer
 *
 * @author Muhammad Asif
 */
class Customer extends ActiveRecord {

    const className = 'app\common\models\Customer';

    public static function collectionName() {
        return ['fourLink', 'customer'];
    }

    public function attributes() {
        return [
            '_id',
            'customer_id',
            'customer_acc',
            'first_name',
            'last_name',
            'email',
            'account_no',
            'address',
            'address2',
            'city',
            'zip',
            'country',
            'phone',
            'sales_agent',
            'agent_phone',
            'documents',
            'created',
            'created_by',
            'updated',
            'updated_by'
        ];
    }

    public function rules() {
        return [
            [['customer_id', 'customer_acc', 'first_name', 'last_name', 'email', 'account_no', 'address', 'address2', 'city', 'country', 'zip', 'phone', 'sales_agent', 'agent_phone', 'created', 'created_by', 'updated', 'updated_by'], 'safe'],
            ['created', 'default', 'value' => date("Y-m-d H:i:s"), 'on' => 'create'],
            ['created_by', 'default', 'value' => Yii::$app->user->identity->email, 'on' => 'create'],
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated',
                ],
                'value' => date("Y-m-d H:i:s"),
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_by',
                ],
                'value' => (isset(Yii::$app->user->identity->email) ? Yii::$app->user->identity->email : ''),
            ], //other behaviors
        ];
    }

}

// end class
