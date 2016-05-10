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
 * Description of Plans
 *
 * @author Muhammad Asif
 */
class Plans extends ActiveRecord {
    
    const className = 'app\common\models\SunlineCustomer';

    public static function collectionName() {
        return ['fourLink', 'plans'];
    }

    public function attributes() {
        return [
            '_id',
            'name',
            'plan_group',
            'plan_type',
            'contract_period',
            'mrc',
            '4link_points'
        ];
    }
    
    public function rules() {
        return [
            [['name', 'plan_group', 'plan_type', 'contract_period', 'mrc', '4link_points'], 'safe'],
        ];
    }
    
} // end class