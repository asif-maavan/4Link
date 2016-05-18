<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use Yii;
use yii\base\Model;
use app\common\models\AccountType;

/**
 * Description of AccountTypeForm
 *
 * @author E-Teck Laptops
 */
class AccountTypeForm extends Model {
    public $_id;
    public $type_name;
    public $created;
    public $created_by;
    public $updated;
    public $updated_by;
    
    public function rules() {
        return [
            [['type_name'], 'required'],
            [['_id', 'created', 'created_by'], 'safe'],
            ['created', 'default', 'value' => date("Y-m-d H:i:s"), 'on' => 'create'],
            ['created_by', 'default', 'value' => Yii::$app->user->identity->email, 'on' => 'create'],
        ];
    }// end function
    
    public function createOrUpdate() {
        if (isset($this->_id)) {
            $id = $this->_id;
            //$this->scenario = 'update';
        } else {
            $id = '';
            $this->scenario = 'create';
        }
        if ($this->validate()) {
            if ($id != "") {
                $accType = AccountType::findOne($id);
            } else {
                $accType = new AccountType();
            }
            $accType->attributes = $this->attributes;
            if ($accType->save()) {
                return ['msgType' => 'SUC'];
            } else {
                $errors = $accType->getErrors();
                return ['msgType' => 'ERR', 'msgArr' => $errors];
            }
        } else {
            $errors = $this->getErrors();
            return ['msgType' => 'ERR', 'msgArr' => $errors];
        }
    }
    
} //end class
