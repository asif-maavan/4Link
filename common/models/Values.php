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
class Values extends ActiveRecord {

    const className = 'app\common\models\Values';

    public static function collectionName() {
        return ['fourLink', 'values'];
    }

    public function attributes() {
        return [
            '_id',
            'name',
            'value',
            'created',
            'created_by',
            'updated',
            'updated_by'
        ];
    }

    public function rules() {
        return [
            [['name', 'value'], 'safe'],
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
