<?php

namespace app\components;

use Yii;
use app\common\models\Countries;
use app\common\models\User;

class GlobalFunction {

    public static function dateDiff($d1, $d2, $date = FALSE) {
        $d1 = str_replace('/', '-', $d1);
        $d2 = str_replace('/', '-', $d2);
        $date1 = new \DateTime($d1);
        $date2 = new \DateTime($d2);
        $diff = date_diff($date1, $date2);
        if ($date) {
            return $diff;
        }
        return $diff->format("%a");
    }

    public static function getUserRoles() {
        return [User::ROLE_ADMIN => 'Admin',
            User::ROLE_manager => 'Manager',
            User::ROLE_supervisor => 'Supervisor',
            User::ROLE_executive => 'Sales Executive',
            User::ROLE_operator => 'Operator',];
    }

    public static function getPlanTypeList() {
        return [1 => 'Post-Paid',
            2 => 'Pre-Paid'];
    }

    public static function getSalesDetailStatesList() {
        return ['Activated' => 'Activated',
            'Rejected' => 'Rejected',
            'Cancelled' => 'Cancelled'];
    }

    public static function getExecutiveStats($id) {
        $className = 'app\common\models\Sales';
        $whereParams ['sale_executive._id'] = '' . $id;
        $params = ['className' => $className, 'whereParams' => $whereParams, 'nameS' => '', 'sort' => '_id', 'selectParams' => ['_id', 'created', 'submitted', 'total_MRC_per_order', 'total_FLP_per_order', 'est_actual_difference']];
        $data = GlobalFunction::getListing($params);
        $stats = [];

        $n = count($data);
        //echo $id . ' - ' . $n . '<br>';
        if ($n > 0) {

            $totalMRC = $totalFLP = $delayed = 0;
            foreach ($data as $value) {
                if ($value->est_actual_difference > 0) {
                    ++$delayed;
                }
                $totalMRC += $value->total_MRC_per_order;
                $totalFLP += $value->total_FLP_per_order;
                $stats['last_sale_date'] = $value->created;
            }
            $stats['total_MRC'] = $totalMRC;
            $stats['avg_MRC'] = intval($totalMRC / $n);
            $stats['total_FLP'] = $totalFLP;
            $stats['avg_FLP'] = intval($totalFLP / $n);
            $stats['delayed'] = $delayed;
        }
        return $stats;
    }

    public static function getReportToList($id, $role) {
        $className = 'app\common\models\User';
        $role = (intval($role) == 5) ? intval($role) - 2 : intval($role) - 1;

        if ($id == 'new') {
            $whereParams = ['between', 'user_role', 2, $role];
        } else {
            $whereParams = ['and', ['not', '_id', new \MongoId($id)], ['between', 'user_role', 2, $role]];
        }

        $params = ['className' => $className, 'whereParams' => $whereParams, 'nameS' => '', 'sort' => 'first_name', 'selectParams' => ['_id', 'user_id', 'first_name', 'last_name']];
        $data = GlobalFunction::getListing($params);
        $list = [];
        //var_dump($data);
        if (count($data) > 0) {
            foreach ($data as $value) {
                $list[$value->_id->{'$id'}] = $value->first_name . ' ' . $value->last_name;
            }
        } else {
            $list[1] = 'No One';
        }
        return $list;
    }

    public static function getTeamLeadList() {
        $className = 'app\common\models\User';

        $whereParams = ['between', 'user_role', 2, 3];

        $params = ['className' => $className, 'whereParams' => $whereParams, 'nameS' => '', 'sort' => 'first_name', 'selectParams' => ['_id', 'user_id', 'first_name', 'last_name']];
        $data = GlobalFunction::getListing($params);
        $list = [];
        //var_dump($data);
        if (count($data) > 0) {
            foreach ($data as $value) {
                $list[$value->_id->{'$id'}] = $value->first_name . ' ' . $value->last_name;
            }
        } else {
            $list[1] = 'No One';
        }
        return $list;
    }

    public static function getAgentList() {
        $className = 'app\common\models\User';

        $whereParams = ['user_role' => User::ROLE_executive];
        $params = ['className' => $className, 'whereParams' => $whereParams, 'nameS' => '', 'sort' => 'first_name', 'selectParams' => ['_id', 'user_id', 'first_name', 'last_name']];
        $data = GlobalFunction::getListing($params);
        $list = [];
        //var_dump($data);
        if (count($data) > 0) {
            foreach ($data as $value) {
                $list[$value->_id->{'$id'}] = $value->first_name . ' ' . $value->last_name;
            }
        }
        return $list;
    }

    public static function getCustomerTypes() {
        return['1' => 'New',
            '2' => 'Existing'];
    }

    public static function getYN() {
        return['1' => 'Yes',
            '2' => 'NO'];
    }

    public static function getOrderStateList() {
        return['Verified' => 'Verified',
            'Submitted to FIN' => 'Submitted to FIN',
            'FIN Approved' => 'FIN Approved',
            'Submitted to AT' => 'Submitted to AT',
            'AT Approved' => 'AT Approved',
            'SO Assigned' => 'SO Assigned',
            'Activated' => 'Activated',
            'Rejected' => 'Rejected',
            'Cancelled' => 'Cancelled'];
    }

    public static function getOrderTypeList() {
        $className = 'app\common\models\OrderType';
        $params = ['className' => $className, 'whereParams' => '', 'nameS' => '', 'sort' => 'type_name', 'selectParams' => ['_id', 'type_name']];
        $data = GlobalFunction::getListing($params);
        $list = [];
        //var_dump($data);
        if (count($data) > 0) {
            foreach ($data as $value) {
                $list[$value->_id->{'$id'}] = $value->type_name;
            }
        }
        return $list;
    }

    public static function getCustomerList() {
        $className = 'app\common\models\Customer';
        $params = ['className' => $className, 'whereParams' => '', 'nameS' => '', 'sort' => 'first_name', 'selectParams' => ['_id', 'first_name']];
        $data = GlobalFunction::getListing($params);
        $list = [];
        //var_dump($data);
        if (count($data) > 0) {
            foreach ($data as $value) {
                $list[$value->_id->{'$id'}] = $value->first_name;
            }
        }
        return $list;
    }

    public static function getPlansList() {
        $className = 'app\common\models\Plans';
        $params = ['className' => $className, 'whereParams' => '', 'nameS' => '', 'sort' => 'name', 'selectParams' => ['_id', 'name']];
        $data = GlobalFunction::getListing($params);
        $list = [];
        //var_dump($data);
        if (count($data) > 0) {
            foreach ($data as $value) {
                $list[$value->_id->{'$id'}] = $value->name;
            }
        }
        return $list;
    }

//    ,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,
    public static function getMonths() {
        return ['01' => 'Jan',
            '02' => 'Feb',
            '03' => 'Mar',
            '04' => 'Apr',
            '05' => 'May',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Aug',
            '09' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec'];
    }

    public static function getDates() {
        for ($i = 1; $i <= 31; $i++) {
            $value = $i;
            if ($i < 10) {
                $value = str_pad($i, 2, "0", STR_PAD_LEFT);
            }
            $dateArr[$value] = $value;
        }
        return $dateArr;
    }

    public static function getCountries() {
        $countriesArr = Countries::getCountries();
        foreach ($countriesArr as $country) {
            $countryArr[$country['code']] = $country['name'];
        }

        return $countryArr;
    }

    public function getData($className, $whereParams) {
        $query = $className::find();
        if ($whereParams) {
            $query->where($whereParams);
        }
        return $query->orderBy(['created' => SORT_DESC])->all();
    }

    public static function findModel($className, $condition) {
        if (($model = $className::findOne($condition)) !== null) {
            return $model;
        } else {
            return false;
        }
    }

    public static function sendMail($params) {
        $emailTo = $params['emailTo'];
        $message = $params['message'];
        $subject = $params['subject'];
        $module = ''; //$params['module'];
        $files = (isset($params['files']) ? $params['files'] : []);
        $emailFrom = ['admin@4link.com' => '4link']; //'4link@4linkadmin.com';

        $message = Yii::$app->mailer->compose('html-email-01', ['message' => $message, 'module' => $module])
                ->setFrom($emailFrom)
                ->setTo($emailTo)
                ->setSubject($subject);

        foreach ($files as $key => $value) {
            $message->attach($key, ['fileName' => $value]);
        }

        $message->send();
        return $message;
    }

    public static function getListing($params) {
        $className = (isset($params['className']) ? $params['className'] : '');
        $pagination = (isset($params['pagination']) ? $params['pagination'] : '');
        $whereParams = (isset($params['whereParams']) ? $params['whereParams'] : '');
        $filterWhereParam = (isset($params['filterWhereParam']) ? $params['filterWhereParam'] : '');
        $selectParams = (isset($params['selectParams']) ? $params['selectParams'] : '');
        $nameS = (isset($params['nameS']) ? trim($params['nameS']) : '');
        $sort = (isset($params['sort']) ? $params['sort'] : '');

        if ($sort != "") {
            if ($sort[0] == '-') {
                $sortBy = SORT_DESC;
                $sortField = ltrim($sort, "-");
                //echo $sortField; exit;
                $sortOrder = [$sortField => SORT_DESC];
            } else {
                $sortBy = SORT_ASC;
                $sortField = $sort;
                $sortOrder = [$sortField => SORT_ASC];
            }
        } else {
            $sortOrder = ['created' => SORT_DESC];
        }

        $query = $className::find();
        $query->select($selectParams);

        if ($whereParams != '')
            $query->andWhere($whereParams);
        if ($filterWhereParam != '') {
            $query->andFilterWhere($filterWhereParam);
        }

        if ($nameS != '') {
            if (preg_match('/\s/', $nameS)) {
                $nameArr = explode(" ", $nameS);
                $query->andWhere(['or', ['LIKE', 'first_name', $nameArr[0], ['LIKE', 'last_name', $nameArr[1]]]]);
            } else {
                //$query->where[0] ='and';
                //$query->where[1] = ['or', ['LIKE', 'first_name', $nameS], ['LIKE', 'last_name', $nameS]];
                $query->andWhere(['or', ['LIKE', 'first_name', $nameS], ['LIKE', 'last_name', $nameS]]);
            }
        }

        if ($pagination)
            $query->offset($pagination->offset)->limit($pagination->limit);

        $query->orderBy($sortOrder);

        return $query->all();
    }

    public function getCount($params) {
        $className = (isset($params['className']) ? $params['className'] : '');
        $whereParams = (isset($params['whereParams']) ? $params['whereParams'] : '');
        $nameS = (isset($params['nameS']) ? $params['nameS'] : '');
        $query = $className::find();
        if ($whereParams != '')
            $query->andWhere($whereParams);
        if ($nameS != '') {
            if (preg_match('/\s/', $nameS)) {
                $nameArr = explode(" ", $nameS);
                $query->andWhere(['or', ['LIKE', 'first_name', $nameArr[0], ['LIKE', 'last_name', $nameArr[1]]]]);
            } else {
                //$query->where[0] ='and';
                //$query->where[1] = ['or', ['LIKE', 'first_name', $nameS], ['LIKE', 'last_name', $nameS]];
                $query->andWhere(['or', ['LIKE', 'first_name', $nameS], ['LIKE', 'last_name', $nameS]]);
            }
        }
        return $query->count();
    }

}

// end class
