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
class ValuesForm extends Model {

    public $_id;
    public $name;
    public $value;
    public $created;
    public $created_by;
    public $updated;
    public $updated_by;

    public function rules() {
        return [
            [['value'], 'required'],
            [['_id', 'name'], 'safe'],
                //['name', ],
        ];
    }

// end function

    public function createOrUpdate() {
//        if (isset($this->_id)) {
//            $id = $this->_id;
//            //$this->scenario = 'update';
//        } else {
//            $id = '';
//            $this->scenario = 'create';
//        }
        if ($this->validate()) {
            $value = \app\common\models\Values::findOne(['name' => $this->name]);
            if (count($value)>0) {
                ;
            } else {
                $value = new \app\common\models\Values();
                $value->scenario = 'create';
            }
            $value->attributes = $this->attributes;
            if ($value->save()) {
                return ['msgType' => 'SUC'];
            } else {
                $errors = $value->getErrors();
                return ['msgType' => 'ERR', 'msgArr' => $errors];
            }
        } else {
            $errors = $this->getErrors();
            return ['msgType' => 'ERR', 'msgArr' => $errors];
        }
    }

}

//end class
