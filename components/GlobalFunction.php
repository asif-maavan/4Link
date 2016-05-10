<?php

namespace app\components;

use Yii;
use app\common\models\Countries;

class GlobalFunction {

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

    public static function getYears() {
        $curYear = date("Y");
        $yTill = $curYear + 10;
        for ($i = $curYear; $i <= $yTill; $i++)
            $yearArr[$i] = $i;

        return $yearArr;
    }

    public static function getYears2() {
        $curYear = date("Y");
        $yTill = $curYear - 11;
        for ($i = $yTill; $i <= $curYear; $i++)
            $yearArr[$i] = $i;

        return $yearArr;
    }

    public static function getCcTypes() {
        return ['American Express' => 'American Express', 'Mastercard' => 'Mastercard', 'Visa' => 'Visa', 'Discover' => 'Discover'];
    }

    public static function getCountries() {
        $countriesArr = Countries::getCountries();
        foreach ($countriesArr as $country) {
            $countryArr[$country['code']] = $country['name'];
        }

        return $countryArr;
    }

    public static function getMileagePrograms() {
        $programArr = ['01' => 'Aeroplan / Air Canada',
            '02' => 'Air France Flying Blue',
            '03' => 'American Airlines AAdvantage',
            '04' => 'American Express Membership Rewards',
            '05' => 'Chase Ultimate Rewards',
            '06' => 'Delta SkyMiles',
            '07' => 'United Mileage Plus',
            '08' => 'Alaska Airlines Mileage Plan',
            '09' => 'British Airways Executive Club/Avios',
            '10' => 'Cathay Pacific / Asia Miles',
            '11' => 'Lufthansa Miles & More',
            '12' => 'Emirates Skywards',
            '13' => 'Virgin Atlantic Flying Club',
            '14' => 'Qatar Airways Privilege Club',
            '15' => 'Starwood Preferred Guest (SPG)',
            '16' => 'Singapore Airlines KrisFlyer',
            '17' => 'Qantas Frequent Flyer',];
        return $programArr;
    }

    public static function airlineList() {
        return ['British Airways' => 'British Airways', 'PIA' => 'PIA'];
    }

    public static function ticketClassList() {
        return ['Business' => 'Business', 'First' => 'First', 'Economy' => 'Economy'];
    }

    public static function ticketBookStatusList() {
        return ['Immediately' => 'Immediately', 'Within A Week' => 'Within A Week', 'Undecided' => 'Undecided'];
    }

    public static function ticketTypesList() {
        return ['Round Trip' => 'Round Trip', 'One Way' => 'One Way'];
    }

    public function ticketFormatList() {
        return ['Award Ticket' => 'Award Ticket', 'Revenue Ticket' => 'Revenue Ticket'];
    }

    public static function mileageAccrualList() {
        return ['Yes' => 'Yes', 'No' => 'No'];
    }

    public static function orderStatusList() {
        return ['1' => 'Enquiry', '2' => 'Info Complete', '3' => 'Authorized', '4' => 'Paid', '5' => 'Invoiced', 6 => 'Completed'];
    }

    public static function paymentTypeList() {
        return ['Credit Card' => 'Credit Card', 'Check' => 'Check', 'Wire Transfer' => 'Wire Transfer', 'Direct Deposit' => 'Direct Deposit', 'PayPal' => 'PayPal', 'Chase QuickPay' => 'Chase QuickPay'];
    }

    public static function passengerList() {
        for ($i = 1; $i <= 10; $i++) {
            $dateArr[$i] = $i;
        }
        return $dateArr;
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
        $module = $params['module'];
        $files = (isset($params['files'])? $params['files'] : []);
        $emailFrom = (isset($params['emailFrom']) && $params['emailFrom'] != "" ? $params['emailFrom'] : ($module == 'sunline' ? ["sunline@sunlineadmin.com" => 'Sunline Team'] : ["sellmileage@sunlineadmin.com" => 'Sellmileage Team']));
        
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

    public function makePayment($params) {
        $status = 'Not Done';
        $amount = $params['amount'];
        $name = $params['name'];
        $expMonth = $params['exp_month'];
        $creditCardNumber = $params['credit_card_number'];
        $csc = (YII_ENV == 'dev') ? '999' : $params['csc'];
        $expYear = $params['exp_year'];
        $address = $params['address'];
        $email = $params['email'];

        //format the parameter string to process a transaction through PayTrace
        $parmlist = "parmlist=" . urlencode("UN~maavan|PSWD~m@vaAn321#|TERMS~Y|");
        //$parmlist = "parmlist=" . urlencode("UN~info@sunlinetravels.com|PSWD~lahore786|TERMS~Y|");
        $parmlist .= urlencode("METHOD~ProcessTranx|TRANXTYPE~Sale|");
        $parmlist .= urlencode("CC~" . $creditCardNumber . "|EXPMNTH~" . $expMonth . "|EXPYR~" . $expYear . "|");  // CC~4012881888818888
        $parmlist .= urlencode("AMOUNT~" . $amount . "|CSC~" . $csc . "|BNAME~" . $name . "|"); // 999
        $parmlist .= urlencode("BADDRESS~" . $address . "|BADDRESS2~" . $params['address2'] . "|BSTATE~" . $params['state'] . "|BCITY~" . $params['city'] . "|BZIP~" . $params['zip'] . "|email~" . $email . "|BCOUNTRY~" . $params['country'] . "|"); //10001

        $header = array("MIME-Version: 1.0", "Content-type: application/x-www-form-urlencoded", "Contenttransfer-encoding: text");

        //point the cUrl to PayTrace's servers
        $url = "https://paytrace.com/api/default.pay";

        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);



        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parmlist);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        // grab URL and pass it to the browser
        $response = curl_exec($ch);

        // close curl resource, and free up system resources
        curl_close($ch);


        //parse through the response.
        $vars = [];
        $responseArr = explode('|', $response);
        foreach ($responseArr as $pair) {
            if ($pair != "") {
                $tmp = explode('~', $pair);
                $vars[$tmp[0]] = $tmp[1];
            }
        }

        $approved = False;

        //search through the name/value pairs for the APCODE
        $ErrorMessage = "";
        foreach ($vars as $key => $value) {

            if ($key == "APPCODE") {
                if ($value != "") {
                    $approved = True;
                }
            } elseif ($key == "ERROR") {
                $ErrorMessage .= $value;
            }
        } // end for loop
        $tid = '';
        if ($ErrorMessage != "") {
            $status = 'Not Done';
            //echo "Your transaction was not successful per this response, " . $ErrorMessage . "<br>";
            //Not approved because an error caught by PayTrace (i.e. invalid card number, amount, etc.)
        } else {

            if ($approved == True) {
                //echo "Your transaction was successfully approved.<br>";
                $status = 'Done';
                $tid = $vars['TRANSACTIONID'];
            } else {
                //echo "Your transaction was not successful was not approved.<br>";
                $status = 'Not Done';
                //Not approved by issuing bank.
            } //end if transaction was approved
        } //end if error message

        return ['response' => $response, 'status' => $status, 'transactionId' => $tid];
    }

}
