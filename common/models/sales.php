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
 * Description of sales
 *
 * @author Muhammad Asif
 */
class sales extends ActiveRecord {

    const className = 'app\common\models\SunlineCustomer';

    public static function collectionName() {
        return ['fourLink', 'sales'];
    }

    public function attributes() {
        return [
            '_id',
            'uid',
            'index_no',
            'sale_executive',
            'customer_type',
            'order_type',
            'customer_acc_no',
            'customer_name',
            'require_finance',
            'sale_no',
            'team_leader',
            'account_type',
            'documents',
            'plan', //plane
            'plan_group',
            'plan_type',
            'QTY',
            'MRC',
            'contract_period',
            'contract_renewal_date',
            'pipe_line',
            'siebel_activity_no',
            'four_link_points',
            'f_indicator', // finance
            'require_finance',
            'order_sub_&_finance_sub_difference',
            'submitted_to_finance',
            'f_response',
            'f_state',
            'f_comments',
            'AT_indicator', // account transfer
            'require_account_transfer',
            'order_sub_&_AT_sub_difference',
            'submitted_to_AT',
            'AT_response',
            'AT_state',
            'AT_comments',
            'LD_indicator', // logistics delivery  
            'require_logistic_dep',
            'LD_state',
            'sale_no',
            'submitted_to_LD',
            'LD_response',
            'LD_comments',
            'RG_indicator',
            'require_resolver_group',
            'submitted_to_RG',
            'RG_response',
            'RG_state',
            'RG_comments',
            'order_state', // order state
            'date_of_order_state',
            'estimated_activation_date',
            'est_&_submission_difference',
            'est_&_actual_difference',
            'total_MRC_per_order',
            'total_FLP_per_order'
        ];
    }

    public function rules() {
        return [
            [['uid', 'index_no', 'sale_executive',
            'customer_type', 'order_type', 'customer_acc_no', 'customer_name', 'require_finance', 'sale_no', 'team_leader', 'account_type', 'documents',
            'plan', //plane
            'plan_group', 'plan_type', 'QTY', 'MRC', 'contract_period', 'contract_renewal_date', 'pipe_line', 'siebel_activity_no', 'four_link_points',
            'f_indicator', // finance
            'require_finance', 'order_sub_&_finance_sub_difference', 'submitted_to_finance', 'f_response', 'f_state', 'f_comments',
            'AT_indicator', // account transfer
            'require_account_transfer', 'order_sub_&_AT_sub_difference', 'submitted_to_AT', 'AT_response', 'AT_state', 'AT_comments',
            'LD_indicator', // logistics delivery  
            'require_logistic_dep', 'LD_state', 'sale_no', 'submitted_to_LD', 'LD_response', 'LD_comments', 'RG_indicator', 'require_resolver_group',
            'submitted_to_RG', 'RG_response', 'RG_state', 'RG_comments',
            'order_state', // order state
            'date_of_order_state', 'estimated_activation_date', 'est_&_submission_difference', 'est_&_actual_difference', 'total_MRC_per_order',
            'total_FLP_per_order'], 'safe'],
        ];
    }

}

// end class
