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
 * Description of AccountType
 *
 * @author Muhammad Asif
 */
class AccountType extends ActiveRecord {

    const className = 'app\common\models\SunlineCustomer';

    public static function collectionName() {
        return ['fourLink', 'account_type'];
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

}

// end class
