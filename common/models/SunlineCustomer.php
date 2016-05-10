<?php

namespace app\common\models;

use Yii;
use yii\mongodb\ActiveRecord;
use yii\behaviors\AttributeBehavior;

class SunlineCustomer extends ActiveRecord {

    const className = 'app\common\models\SunlineCustomer';

    public static function collectionName() {
        return ['sunline', 'sl_customers'];
    }

    public function attributes() {
        return [
            '_id',
            'customer_id',
            'prefix',
            'first_name',
            'last_name',
            'email',
            'dob',
            'gender',
            'phone',
            'passport_number',
            'passport_issue_date',
            'passport_expire_date',
            'nationality',
            'traveller_number',
            'credit',
            'credit_card_type',
            'credit_card_number',
            'credit_card_expire_date',
            'credit_card_security_code',
            'address1',
            'address2',
            'state',
            'city',
            'zip',
            'country',
            'country',
            'billing_address1',
            'billing_address2',
            'billing_state',
            'billing_city',
            'billing_zip',
            'billing_country',
            'admin_comment',
            'user_comment',
            'unique_string',
            'created',
            'created_by',
            'updated',
            'updated_by',
            'is_active',
            'admin_interact'
        ];
    }

//    public function beforeSave($insert) {
//        parent::beforeSave($insert);
//
//        $this->dob = $this->dob_year . '/' . $this->dob_month . '/' . $this->dob_date;
//        $this->passport_issue_date = $this->passport_issue_month . '/' . $this->passport_issue_year;
//        $this->passport_expire_date = $this->passport_expire_month . '/' . $this->passport_expire_year;
//        $this->credit_card_expire_date = $this->credit_card_expire_month . '/' . $this->credit_card_expire_year;
//
//        unset($this->dob_year);
//        unset($this->dob_month);
//        unset($this->dob_date);
//        unset($this->passport_issue_month);
//        unset($this->passport_issue_year);
//        unset($this->passport_expire_month);
//        unset($this->passport_expire_year);
//        unset($this->credit_card_expire_month);
//        unset($this->credit_card_expire_year);
//
//        return $this;
//    }
//
//    public function afterFind() {
//        parent::afterFind();
//
//        $explodedArr = explode('/', $this->dob);
//        if (count($explodedArr) > 1) {
//            $this->dob_year = $explodedArr[0];
//            $this->dob_month = $explodedArr[1];
//            $this->dob_date = $explodedArr[2];
//        }
//
//        $explodedArr = explode('/', $this->passport_issue_date);
//        if (count($explodedArr) > 1) {
//            $this->passport_issue_month = $explodedArr[0];
//            $this->passport_issue_year = $explodedArr[1];
//        }
//        
//        $explodedArr = explode('/', $this->passport_expire_date);
//        if (count($explodedArr) > 1) {
//            $this->passport_expire_month = $explodedArr[0];
//            $this->passport_expire_year = $explodedArr[1];
//        }
//        
//        $explodedArr = explode('/', $this->credit_card_expire_date);
//        if (count($explodedArr) > 1) {
//            $this->credit_card_expire_month = $explodedArr[0];
//            $this->credit_card_expire_year = $explodedArr[1];
//        }
//
//        return $this;
//    }

    public function rules() {
        return [
            [['prefix', 'first_name', 'last_name', 'email', 'gender', 'phone', 'passport_number', 'traveller_number', 'nationality', 'credit_card_type', 'credit_card_number', 'credit_card_security_code', 'address1', 'address2', 'state', 'city', 'zip', 'country', 'billing_address1', 'billing_address2', 'billing_state', 'billing_city', 'billing_zip', 'billing_country', 'dob', 'passport_issue_date', 'passport_expire_date', 'credit_card_expire_date', 'admin_comment', 'credit'], 'safe'],
            ['created', 'default', 'value' => date("Y-m-d H:i:s"), 'on' => 'create'],
            ['created_by', 'default', 'value' => (isset(Yii::$app->user->identity->email) ? Yii::$app->user->identity->email : ''), 'on' => 'create'],
            ['is_active', 'default', 'value' => 'Y', 'on' => 'create'],
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

    public static function createCustomer($data) {
        $model = new SunlineCustomer();
        $model->scenario = 'create';
        $model->attributes = $data;
        $data = SunlineCustomer::find()->select(['customer_id'])->orderBy(['_id' => SORT_DESC])->one();
        if (!empty($data)) {
            $tmpStr = substr($data->customer_id, 2);
            $tmpStr++;
        } else {
            $tmpStr = 1;
        }

        $tmpStr = "SL" . $tmpStr;
        $model->customer_id = $tmpStr;
        if($model->save())
            return $tmpStr;
        else
            return false;
    }

}
