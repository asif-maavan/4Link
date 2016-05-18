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
            'type_name',
            'created',
            'created_by',
            'updated',
            'updated_by'
        ];
    }

    public function rules() {
        return [
            [['type_name', 'created', 'created_by', 'updated', 'updated_by'], 'safe'],
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