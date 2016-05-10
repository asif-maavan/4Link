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
 * Description of OrderType
 *
 * @author Muhammad Asif
 */
class OrderType extends ActiveRecord {
    
    const className = 'app\common\models\SunlineCustomer';

    public static function collectionName() {
        return ['fourLink', 'order_type'];
    }

    public function attributes() {
        return [
            '_id',
            'type_name'
        ];
    }
    
    public function rules() {
        return [
            [['type_name'], 'safe'],
        ];
    }
    
} // end class