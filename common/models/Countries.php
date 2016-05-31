<?php

namespace app\common\models;

use Yii;
use yii\mongodb\ActiveRecord;

class Countries extends ActiveRecord {

    public static function collectionName() {
        return ['sunline', 'countries'];
    }

    public function attributes() {
        return [
            '_id',
            'name',
            'code'
        ];
    }

    public static function getCountries() {

        $query = Countries::find();
        $query->select(['code', 'name']);    
        return $query->all();
    }

    public function getCount() {
        $query = Countries::find();
        return $query->count();
    }

}
