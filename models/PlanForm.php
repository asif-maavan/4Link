<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use Yii;
use yii\base\Model;
use app\common\models\Plans;

/**
 * Description of PlanForm
 *
 * @author E-Teck Laptops
 */
class PlanForm extends Model {

    public $_id;
    public $name;
    public $plan_group;
    public $plan_type;
    public $contract_period;
    public $mrc;
    public $fourlink_points;

    public function rules() {
        return [
            [['name', 'plan_group', 'plan_type', 'contract_period', 'mrc', 'fourlink_points'], 'required'],
            [['_id'], 'safe'],
        ];
    }

    public function createOrUpdate() {
        if (isset($this->_id)) {
            $id = $this->_id;
            //$this->scenario = 'update';
        } else {
            $id = '';
            //$this->scenario = 'create';
        }
        if ($this->validate()) {
            if ($id != "") {
                $plan = Plans::findOne($id);
            } else {
                $plan = new Plans();
            }
            $plan->attributes = $this->attributes;
            if ($plan->save()) {
                return ['msgType' => 'SUC'];
            } else {
                $errors = $plan->getErrors();
                return ['msgType' => 'ERR', 'msgArr' => $errors];
            }
        } else {
            $errors = $this->getErrors();
            return ['msgType' => 'ERR', 'msgArr' => $errors];
        }
    }

}

// end class
