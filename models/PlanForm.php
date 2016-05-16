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
    public $created;
    public $created_by;

    public function rules() {
        return [
            [['name', 'plan_group', 'plan_type', 'contract_period', 'mrc', 'fourlink_points'], 'required'],
            [['contract_period', 'mrc', 'fourlink_points'], 'number'],
            [['_id', 'created', 'created_by'], 'safe'],
            ['created', 'default', 'value' => date("Y-m-d H:i:s"), 'on' => 'create'],
            ['created_by', 'default', 'value' => Yii::$app->user->identity->email, 'on' => 'create'],
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
            $plan->plan_type = intval($this->plan_type);
            $plan->contract_period = intval($this->contract_period);
            $plan->mrc = intval($this->mrc);
            $plan->fourlink_points = intval($this->fourlink_points);
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
