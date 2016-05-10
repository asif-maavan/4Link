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

    const className = 'app\common\models\SunlineCustomer';

    public static function collectionName() {
        return ['fourLink', 'customer'];
    }

    public function attributes() {
        return [
            '_id',
            'uid',
            'customer_acc',
            'name',
            'account_no',
            'address',
            'phone',
            'sales_agent',
            'agent_phone'
        ];
    }

    public function rules() {
        return [
            [['uid', 'customer_acc', 'name', 'account_no', 'address', 'phone', 'sales_agent', 'agent_phone'], 'safe'],
        ];
    }

}

// end class
