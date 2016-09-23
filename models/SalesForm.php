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
    public $submitted;
    public $documents;
    public $plan;       // Plan
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
    public $so_no;
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
    public $created;

    // Rules
    public function rules() {
        return [
            [['index_no', 'sale_executive', 'customer_type', 'order_type', 'customer_acc_no', 'customer_name', 'plan'], 'required', 'on' => ['create', 'update']],
            [['_id', 'uid', 'submitted_to_AT', 'order_state', 'created', 'siebel_activity_no', 'require_finance', 'require_account_transfer', 'so_no'], 'safe'],
            [['sale_executive', 'order_type', 'customer_name', 'plan', 'QTY', 'siebel_activity_no', 'submitted'], 'required', 'on' => 'detail'],
            [['index_no', 'plan_group', 'plan_type', 'team_leader', 'customer_type', 'customer_acc_no', 'account_type', 'QTY', 'MRC', 'contract_period', 'four_link_points', 'f_indicator', 'submitted_to_finance', 'f_response', 'f_state', 'f_comments', 'AT_indicator', 'AT_response', 'AT_state', 'AT_comments', 'LD_indicator', 'LD_state', 'submitted_to_LD', 'LD_response', 'LD_comments', 'RG_indicator', 'submitted_to_RG', 'RG_response', 'RG_state', 'RG_comments', 'require_logistic_dep', 'require_resolver_group', 'date_of_order_state', 'estimated_activation_date', 'est_submission_difference', 'est_actual_difference', 'total_MRC_per_order', 'total_FLP_per_order', 'so_no'], 'safe', 'on' => 'detail'],
            [['customer_acc_no', 'QTY'], 'number'],
            [['customer_name'], 'match', 'pattern' => '/^[a-zA-z ]*$/', 'when' => function($model) {
                    return (isset($model->customer_type) && $model->customer_type == '1');
                }, 'enableClientValidation' => false, 'on'=>'create','message' => Yii::t('app', 'Use Alphabets only.')],
            ['index_no', 'validateIndex'], //, 'on' => ['create', 'update']
                //['so_no', 'validateSaleNo'],
        ];
    }

    public function validateIndex($attribute, $params) {
        $whereParams = ['and', ['not', '_id', new \MongoId($this->_id)], ['index_no' => $this->index_no]];
        $models = \app\components\GlobalFunction::getListing(['className' => 'app\common\models\Sales', 'whereParams' => $whereParams, 'selectParams' => ['index_no']]);
        if (count($models) > 0) {
            //echo count($models);
            $this->addError($attribute, 'This Index No is already taken');
        }
    }

//    public function validateSaleNo($attribute, $params) {
//        $whereParams = ['and', ['not', '_id', new \MongoId($this->_id)], ['so_no' => $this->so_no]];
//        $models = \app\components\GlobalFunction::getListing(['className' => 'app\common\models\Sales', 'whereParams' => $whereParams, 'selectParams' => ['index_no']]);
//        if (count($models) > 0) {
//            //echo count($models);
//            $this->addError($attribute, 'This Sale No is already taken');
//        }
//    }
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
            $params['customer_name'] = '' . $this->customer_name;

            $orderStateDate = $sale->date_of_order_state;
            $arcDate = $sale->date_ARC;
            $verifiedDate = $sale->submitted;
            if (isset($params['order_state']) && $sale->order_state != $params['order_state']) {
                $orderStateDate = date("Y-m-d H:i:s");
                if ($params['order_state'] == 'Activated' || $params['order_state'] == 'Rejected' || $params['order_state'] == 'Cancelled') {
                    $arcDate = new \MongoDate ();
                }
            }

            $isRequireFIN = $sale->require_finance;
            $isRequireAT = $sale->require_account_transfer;
            $fState = $sale->f_state;
            $atState = $sale->AT_state;

            $sale->attributes = $params; //$this->attributes;
            if ($this->scenario == 'create' && !$this->order_state) {
                $sale->order_state = 'Created';
                $orderStateDate = date("Y-m-d H:i:s");
            }
            if($this->order_state==''){
                $sale->order_state = 'Created';
            }

            $sale->date_of_order_state = $orderStateDate;
            $executive = \app\common\models\User::findOne($sale->sale_executive);
            $plan = \app\common\models\Plans::findOne($sale->plan);
            $sale->sale_executive = ['_id' => $sale->sale_executive, 'name' => \app\components\GlobalFunction::getAgentList()[$sale->sale_executive]];
            $sale->team_leader = $executive->report_to;
            $sale->order_type = ['_id' => $sale->order_type, 'name' => \app\components\GlobalFunction::getOrderTypeList()[$sale->order_type]];
            $this->customer_name = $sale->customer_name = ['_id' => $sale->customer_name, 'name' => \app\components\GlobalFunction::getCustomerList()[$sale->customer_name]];
            $sale->plan = ['_id' => $sale->plan, 'name' => \app\components\GlobalFunction::getPlansList()[$sale->plan]];
            $sale->plan_group = $plan->plan_group;
            $sale->plan_type = $plan->plan_type;
            $sale->MRC = $plan->mrc;
            $sale->contract_period = $plan->contract_period;
            $sale->four_link_points = $plan->fourlink_points;
            $estDate = $this->getEstDate();            
            $sale->date_ARC = $arcDate;
//            order state values after calculations
            $sale->est_actual_difference = ($this->date_of_order_state && $estDate != '-') ? \app\components\GlobalFunction::dateDiff($estDate->format('d/m/Y'), date('d/m/Y', strtotime($this->date_of_order_state)),TRUE)->format("%R%a") : '';
            $sale->total_MRC_per_order = (!empty($this->MRC) && !empty($this->QTY)) ? $this->MRC * $this->QTY : '';
            $sale->total_FLP_per_order = (!empty($this->MRC) && !empty($this->QTY)) ? $this->four_link_points * $this->QTY : '' ;
            
            if ((!empty($verifiedDate) && $sale->submitted != $verifiedDate) || empty($verifiedDate)) {
                $sale->order_state = 'Verified';
            }
            if ($sale->require_finance != $isRequireFIN) {
                $sale->date_require_fin = !empty($sale->require_finance) ? new \MongoDate () : NULL;
            }
            if ($sale->require_account_transfer != $isRequireAT) {
                $sale->date_require_at = !empty($sale->require_account_transfer) ? new \MongoDate () : NULL;
            }
            if ($sale->f_state != $fState) {
                $sale->date_fin_approved = !empty($sale->f_state) ? new \MongoDate () : NULL;
                $sale->order_state = ($sale->f_state == 'Activated') ? 'FIN Approved' : $sale->order_state;
            }
            if ($sale->AT_state != $atState) {
                $sale->date_at_approved = !empty($sale->AT_state) ? new \MongoDate () : NULL;
                $sale->order_state = ($sale->AT_state == 'Activated') ? 'AT Approved' : $sale->order_state;
            }

            if ($this->require_finance != '1') {
                $sale->f_indicator = $this->f_indicator = '-';
                $sale->f_state = $this->f_state = '';
                $sale->submitted_to_finance = $this->submitted_to_finance = '';
                $sale->f_response = $this->f_response = '-';
            }
            if ($this->require_account_transfer != '1') {
                $sale->AT_indicator = $this->AT_indicator = '-';
                $sale->AT_state = $this->AT_state = '';
                $sale->submitted_to_AT = $this->submitted_to_AT = '';
                $sale->AT_response = $this->AT_response = '-';
            }

            if ($this->scenario == 'detail') {
                if ($this->require_logistic_dep != '1') {
                    $sale->LD_indicator = $this->LD_indicator = '-';
                    $sale->LD_state = $this->LD_state = '';
                    $sale->submitted_to_LD = $this->submitted_to_LD = '';
                    $sale->LD_response = $this->LD_response = '-';
                }
                if ($this->require_resolver_group != '1') {
                    $sale->RG_indicator = $this->RG_indicator = '-';
                    $sale->RG_state = $this->RG_state = '';
                    $sale->submitted_to_RG = $this->submitted_to_RG = '';
                    $sale->RG_response = $this->RG_response = '-';
                }
            }

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
    
    public function getEstDate() {
        $est = \app\components\GlobalFunction::getEstList();
        $date = '-';
        if ($this->submitted) {
            $date = $this->submitted;
            $date = str_replace('/', '-', $date);
            $date = new \DateTime($date);

            $date->add(new \DateInterval('P' . $est["Verified"] . 'D'));
            if ($this->require_finance == '1') {
                $date->add(new \DateInterval('P' . $est["Submitted to FIN"] . 'D'));
                $date->add(new \DateInterval('P' . $est["FIN Approved"] . 'D'));
            }
            if ($this->require_account_transfer == '1') {
                $date->add(new \DateInterval('P' . $est["Submitted to AT"] . 'D'));
                $date->add(new \DateInterval('P' . $est["AT Approved"] . 'D'));
            }

            $date->add(new \DateInterval('P' . $est["SO Assigned"] . 'D'));

            $date->add(new \DateInterval('P' . $est["ARC"] . 'D'));
            return $date;
        }
        return $date;
    }

// end class
}
