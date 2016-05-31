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
 * @author Muhammad Asif
 */
class CustomerForm extends Model {

    public $_id;
    public $customer_id;
    public $customer_acc;
    public $first_name;
    public $last_name;
    public $email;
    public $account_no;
    public $address;
    public $address2;
    public $city;
    public $zip;
    public $country;
    public $phone;
    public $sales_agent;
    public $agent_phone;
    public $created;
    public $created_by;

    public function rules() {
        return [
            [['first_name', 'customer_acc', 'account_no', 'address', 'phone', 'sales_agent', 'agent_phone'], 'required', 'on' => ['default', 'detail']],
            [['first_name', 'customer_acc'], 'required', 'on' => 'createFromSale'],
            [['last_name', 'email', 'account_no', 'address', 'address2', 'city', 'zip','phone', 'sales_agent', 'agent_phone'], 'safe', 'on' => 'createFromSale'],
//            [['first_name', 'customer_acc', 'account_no', 'email', 'phone','sales_agent', 'agent_phone'], 'required', 'on' => 'detail'],
            [['phone', 'agent_phone'], 'match', 'pattern' => '/^[0-9-]+$/', 'message' => 'only numeric characters and dashes are allowed.'],
            ['email', 'email'],
            [['_id', 'customer_id', 'last_name', 'account_no', 'email', 'address2', 'city', 'country', 'zip'], 'safe'],
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
            $customer->attributes = $params; //$this->attributes;
            $customer->sales_agent = ['_id' => $customer->sales_agent, 'name' => \app\components\GlobalFunction::getAgentList()[$customer->sales_agent]];

            if ($customer->save()) {
                $this->_id = $customer->_id;
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
