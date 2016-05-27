<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use Yii;
use yii\base\Model;
use app\common\models\Sales;

/**
 * Description of SalesForm
 *
 * @author Muhammad Asif
 */
class SalesForm extends Model {

    public $_id;
    public $uid;
    public $index_no;
    public $sale_executive;
    public $customer_type;
    public $order_type;
    public $customer_acc_no;
    public $customer_name;
    public $team_leader;
    public $account_type;
    public $documents;
    public $plan;
    public $plan_group;
    public $plan_type;
    public $QTY;
    public $MRC;
    public $contract_period;
    public $contract_renewal_date;
    public $pipe_line;
    public $siebel_activity_no;
    public $four_link_points;
    public $f_indicator; // finance
    public $require_finance;
    public $order_sub_finance_sub_difference;
    public $submitted_to_finance;
    public $f_response;
    public $f_state;
    public $f_comments;
    public $AT_indicator; // account transfer
    public $require_account_transfer;
    public $order_sub_AT_sub_difference;
    public $submitted_to_AT;
    public $AT_response;
    public $AT_state;
    public $AT_comments;
    public $LD_indicator; // logistics delivery  
    public $require_logistic_dep;
    public $LD_state;
    public $sale_no;
    public $submitted_to_LD;
    public $LD_response;
    public $LD_comments;
    public $RG_indicator;
    public $require_resolver_group;
    public $submitted_to_RG;
    public $RG_response;
    public $RG_state;
    public $RG_comments;
    public $order_state; // order state
    public $date_of_order_state;
    public $estimated_activation_date;
    public $est_submission_difference;
    public $est_actual_difference;
    public $total_MRC_per_order;
    public $total_FLP_per_order;

    // Rules
    public function rules() {
        return [
            [['index_no', 'sale_executive', 'customer_type', 'order_type', 'customer_acc_no', 'customer_name', 'plan', 'siebel_activity_no', 'require_finance', 'require_account_transfer', 'sale_no'], 'required', 'on' => ['create', 'update']],
            [['_id','submitted_to_AT', 'order_state'], 'safe'],
        ];
    }

    // create & update
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
                $sale = Sales::findOne($id);
            } else {
                $sale = new Sales();
                $sale->scenario = 'create';
                $data = Sales::find()->select(['uid'])->orderBy(['_id' => SORT_DESC])->one();
                if (!empty($data)) {
                    $tmpStr = $data->uid;
                    $tmpStr++;
                } else {
                    $tmpStr = 1;
                }
                $sale->uid = $tmpStr;
            }
            $params['customer_name'] = ''.$this->customer_name; 
            $sale->attributes = $params; //$this->attributes;
            if ($this->scenario == 'create' && !$this->order_state) {
                $sale->order_state = 'Created';
            }
            $sale->sale_executive = ['_id' => $sale->sale_executive, 'name' => \app\components\GlobalFunction::getAgentList()[$sale->sale_executive]];
            $sale->order_type = ['_id' => $sale->order_type, 'name' => \app\components\GlobalFunction::getOrderTypeList()[$sale->order_type]];
            $sale->customer_name = ['_id' => $sale->customer_name, 'name' => \app\components\GlobalFunction::getCustomerList()[$sale->customer_name]];
            $sale->plan = ['_id' => $sale->plan, 'name' => \app\components\GlobalFunction::getPlansList()[$sale->plan]];

            if ($sale->save()) {
                return ['msgType' => 'SUC'];
            } else {
                $this->errors = $errors = $sale->getErrors();
                return ['msgType' => 'ERR', 'msgArr' => $errors];
            }
        } else {
            $errors = $this->getErrors();
            return ['msgType' => 'ERR', 'msgArr' => $errors];
        }
    }

// end class
}
