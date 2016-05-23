<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use Yii;
use yii\base\Model;
use app\common\models\Customer;

/**
 * Description of CustomerForm
 *
 * @author E-Teck Laptops
 */
class CustomerForm extends Model {

    public $_id;
    public $customer_id;
    public $customer_acc;
    public $first_name;
    public $account_no;
    public $address;
    public $phone;
    public $sales_agent;
    public $agent_phone;
    public $created;
    public $created_by;

    public function rules() {
        return [
            [['first_name', 'customer_acc', 'account_no', 'address', 'phone', 'sales_agent', 'agent_phone'], 'required'],
            [['phone', 'agent_phone'], 'match', 'pattern' => '/^[0-9-]+$/', 'message' => 'only numeric characters and dashes are allowed.'],
            [['_id', 'customer_id'], 'safe'],
        ];
    }

    public function createOrUpdate($params) {
        if (isset($this->_id)) {
            $id = $this->_id;
            //$this->scenario = 'update';
        } else {
            $id = '';
            //$this->scenario = 'create';
        }
        if ($this->validate()) {
            if ($id != "") {//echo 'hi-'.\GuzzleHttp\json_encode($this->attributes); exit();
                $customer = Customer::findOne($id);
            } else {
                $customer = new Customer();
                $customer->scenario = 'create';
                $data = Customer::find()->select(['customer_id'])->orderBy(['_id' => SORT_DESC])->one();
                if (!empty($data)) {
                    $tmpStr = $data->customer_id;
                    $tmpStr++;
                } else {
                    $tmpStr = 1;
                }
                $customer->customer_id = $tmpStr;
            }
            $customer->attributes = $params;//$this->attributes;
            
            if ($customer->save()) {
                return ['msgType' => 'SUC'];
            } else {
                $errors = $customer->getErrors();
                return ['msgType' => 'ERR', 'msgArr' => $errors];
            }
        } else {
            $errors = $this->getErrors();
            return ['msgType' => 'ERR', 'msgArr' => $errors];
        }
    }

}

//end class
