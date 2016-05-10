<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\components\GlobalFunction;
use yii\web\UploadedFile;
use yii\helpers\Url;
use mPDF;

class AjaxController extends AppController {

    public function actionRqstInfoEmail() {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post("id");
            $type = Yii::$app->request->post("type");
            $string = md5(time() . rand(1000, 9999));
            if (strstr($_SERVER['HTTP_REFERER'], "sunline/customer")) {
                $data = GlobalFunction::findModel('app\common\models\SunlineCustomer', $id);
                $fname = $data->first_name;
                $email = $data->email;
                $url = "https://www.sunlinetravels.com/request-info.php?string=$string";
                $message = "Dear $fname,<br /><br />Please <a href='$url'>click here</a> to fill out your profile page. Once we have all the required information we will be able to issue your ticket.<br /><br />Thank you for choosing Sunline Travel. ";
                $subject = 'Information Request: Please update your profile with Sunline Travel';
                $module = 'sunline';
                $data->unique_string = $string;
            } else if (strstr($_SERVER['HTTP_REFERER'], "sellmileage/customer")) {
                $data = GlobalFunction::findModel('app\common\models\SellmileageCustomer', $id);
                $fname = $data->first_name;
                $email = $data->email;
                $url = Url::base(true) . "/sellmileage/facing/personalinfo?string=$string";
                $message = "Dear $fname,<br /><br />Please <a href='$url'>click here</a> to add the missing information.<br /><br />Thanks,";
                $subject = 'Information Request: Please update your profile with Sellmileage Inc.';
                $module = 'sellmileage';
                $data->unique_string = $string;
            } else if (strstr($_SERVER['HTTP_REFERER'], "sunline/order")) {
                $orderData = GlobalFunction::findModel('app\common\models\SunlineOrder', $id);
                $data = GlobalFunction::findModel('app\common\models\SunlineCustomer', ['customer_id' => $orderData->customer_id]);
                $fname = $data->first_name;
                $email = $data->email;
                $module = 'sunline';
                if ($type == 'authorization') {

                    if ($orderData->status != '2') {
                        exit(json_encode(['msgType' => 'ERR', 'msg' => 'Please do not skip the Addition Info step.']));
                    }

                    $orderData->ticket_type = Yii::$app->request->post("ticketType");
                    $orderData->airline = Yii::$app->request->post("airline");

                    $url = "https://www.sunlinetravels.com/request-info.php?string=$string&type=auth";



                    $message = '<h3 style="font-weight:400;font-size:14px;line-height:1.1;margin-bottom:0px;padding-left:10px;padding-top:0px;color:#000;">Dear ' . $fname . ',</h3><br>'; //font-family: \'Open Sans\' !important;
                    $message .= '<p style="padding-left:10px;padding-top:4px;margin-bottom:0px;font-weight:normal;font-size:14px;">Following are the ticket details you have requested:</p>'; //font-family:\'Open Sans\' !important;
                    $message .= '<table width="40%" style="margin-top:20px;margin-left:6px;">';
                    $message .= '<tr><td>RequestID</td><td>' . $orderData->order_id . '</td></tr>';
                    $message .= '<tr><td>Airline</td><td>' . $orderData->airline . '</td></tr>';
                    $message .= '<tr><td>From</td><td>' . $orderData->ticket_from . '</td></tr>';
                    $message .= '<tr><td>To</td><td>' . $orderData->ticket_to . '</td></tr>';
                    $message .= '<tr><td>Trip</td><td>' . $orderData->ticket_type . '</td></tr>';
                    $message .= '<tr><td>Departure Date</td><td>' . date("d M Y", strtotime($orderData->departure_date)) . '</td></tr>';
                    if ($orderData->ticket_type == 'Round Trip')
                        $message .= '<tr><td>Return Date</td><td>' . date("d M Y", strtotime($orderData->return_date)) . '</td></tr>';
                    $message .= '</table>';

                    $message .= '<p style="padding-left:10px;padding-top:4px;margin-bottom:0px;font-weight:normal;font-size:14px;">Thank you for choosing Sunline Travel as your travel agency. Please click on the following link to authorize the payment:</p>';
                    $message .= '<a href="' . $url . '" style="text-decoration:none;color:#2f2f2f;background-color:#f4f4f4;padding:10px 16px;margin-right:10px;text-align:center;border:1px solid #dfdfdf;cursor:pointer;margin-top:30px;display:inline-block;font-size:14px;margin-left:10px;">Click Here</a>'; //font-family:\'Open Sans\' !important;


                    $subject = 'Authorization Request: Please authorize your purchase with Sunline Travel.';
                    $data = $orderData;
                    $data->cost = Yii::$app->request->post("cost");
                    $data->ticket_format = Yii::$app->request->post("ticketFormat");
                    $data->mileage_accrual = Yii::$app->request->post("mileageAcc");
                    $data->other_terms = Yii::$app->request->post("other_terms");
                    $data->unique_string_auth = $string;
                } else {
                    $url = "https://www.sunlinetravels.com/request-info.php?string=$string";
                    $message = "Dear $fname,<br /><br />Please <a href='$url'>click here</a> to fill out your profile page. Once we have all the required information we will be able to issue your ticket.<br /><br />Thank you for choosing Sunline Travel. ";
                    $subject = 'Information Request: Please update your profile with Sunline Travel';
                    $data = $orderData;
                    $data->unique_string = $string;
                }
            } else if (strstr($_SERVER['HTTP_REFERER'], "sellmileage/order")) {
                $orderData = GlobalFunction::findModel('app\common\models\SellmileageOrder', $id);
                $data = GlobalFunction::findModel('app\common\models\SellmileageCustomer', ['customer_id' => $orderData->customer_id]);
                $fname = $data->first_name;
                $email = $data->email;
                $module = 'sellmileage';
                if (isset($_POST['action'])) {
                    if ($_POST['action'] == 'Agreement') {
                        $orderData->cost = Yii::$app->request->post("cost");
                        $orderData->other_terms = Yii::$app->request->post("other_terms");
                        $url = Url::base(true) . "/sellmileage/facing/agreement?string=$string";
                        $message = "Dear $fname,<br /><br />Please <a href='$url'>click here</a> to view and complete your transaction agreement.<br /><br />Thank you for choosing SellMileage.com";
                        $subject = 'Agreement Request: Please fulfill the Agreement with Sellmileage Inc.';
                        $data = $orderData;
                        $data->agreement_unique_string = $string;
                    }
                } else {
                    $url = Url::base(true) . "/sellmileage/facing/personalinfo?string=$string";
                    $message = "Dear $fname,<br /><br />Please <a href='$url'>click here</a> to add the missing information.<br /><br />Thanks,";
                    $subject = 'Information Request: Please update your profile with Sellmileage Inc.';
                    $data = $orderData;
                    $data->unique_string = $string;
                }
            }
            $data->admin_interact = Yii::$app->user->identity->email;
            if ($data->save()) {
                GlobalFunction::sendMail(['emailTo' => $email, 'message' => $message, 'subject' => $subject, 'module' => $module]);
                exit(json_encode(['msgType' => 'SUC', 'msg' => 'Email has been sent']));
            }
        } else {
            throw new \yii\base\Exception("You are not allowed to use this page");
        }
    }

    public function actionUserInfo() {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post("id");
            if (strstr($_SERVER['HTTP_REFERER'], "sunline/order")) {
                $data = GlobalFunction::findModel('app\common\models\SunlineCustomer', $id);
                if ($data) {
                    $retData['fname'] = $data->first_name;
                    $retData['lname'] = $data->last_name;
                    $retData['dob'] = $data->dob;
                    $retData['email'] = $data->email;
                    $retData['customer_id'] = $data->customer_id;
                    $retData['msgType'] = 'SUC';
                    exit(json_encode($retData));
                } else {
                    exit(json_encode(['msgType' => 'ERR']));
                }
            } else if (strstr($_SERVER['HTTP_REFERER'], "sellmileage/order/create")) {
                $data = \app\common\models\SellmileageCustomer::findModel($id);
                if ($data) {
                    $retData['customer_id'] = $data->customer_id;
                    $retData['fname'] = $data->first_name;
                    $retData['lname'] = $data->last_name;
                    $retData['dob'] = $data->dob;
                    $retData['email'] = $data->email;
                    $retData['phone'] = $data->phone;
                    $retData['address1'] = $data->address1;
                    $retData['address2'] = $data->address2;
                    $retData['country'] = $data->country;
                    $retData['msgType'] = 'SUC';
                    $retData['module'] = 'sellmileage';
                    exit(json_encode($retData));
                } else {
                    exit(json_encode(['msgType' => 'ERR']));
                }
            }
        } else {
            throw new \yii\base\Exception("You are not allowed to use this page");
        }
    }

    public function actionUpdatePayment() {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post("id");
            $paymentType = Yii::$app->request->post("paymentType");
            $applyCredit = Yii::$app->request->post("applyCredit");
            $sunlineOrder = GlobalFunction::findModel("\app\common\models\SunlineOrder", $id);
            if ($sunlineOrder->status != '3') {
                exit(json_encode(['msgType' => 'ERR', 'msg' => 'Please do not skip the Authorization step.']));
            }
            $sunlineOrder->payment_type = $paymentType;
            $sunlineOrder->status = '4';
            if ($applyCredit == 'Yes') {
                $custInfo = GlobalFunction::findModel("\app\common\models\SunlineCustomer", ['customer_id' => $sunlineOrder->customer_id]);
                if ($sunlineOrder->cost > $custInfo->credit) {
                    $sunlineOrder->total_cost = $sunlineOrder->cost - $custInfo->credit;
                } else {
                    $sunlineOrder->total_cost = $sunlineOrder->cost;
                }
            } else {
                $sunlineOrder->total_cost = $sunlineOrder->cost;
            }

            // for payment via payTrace API
            $CCPayment = FALSE;
            $transId = '';
            if ($paymentType == 'Credit Card') {
                $creditCardNumber = Yii::$app->security->decryptByKey(utf8_decode($sunlineOrder->credit_card_number), Yii::$app->params['secretKeyCc']);
                $data = GlobalFunction::findModel('app\common\models\SunlineCustomer', ['customer_id' => $sunlineOrder->customer_id]);
                $expireyDate = explode('/', $sunlineOrder->credit_card_expire_date);

                $paymentParem = ['amount' => $sunlineOrder->total_cost,
                    "name" => $sunlineOrder->first_name . ' ' . $sunlineOrder->last_name,
                    "credit_card_number" => $creditCardNumber,
                    "csc" => $sunlineOrder->credit_card_security_code,
                    "exp_month" => $expireyDate[0],
                    "exp_year" => $expireyDate[1],
                    "address" => $sunlineOrder->billing_address1,
                    "address2" => $sunlineOrder->billing_address2,
                    "state" => $sunlineOrder->billing_state,
                    "city" => $sunlineOrder->billing_city,
                    "zip" => $sunlineOrder->billing_zip,
                    "country" => $sunlineOrder->billing_country,
                    "email" => $data->email];

                $result = GlobalFunction::makePayment($paymentParem);

                $log = new \app\common\models\PaymentLog();
                $log->scenario = 'create';
                $log->request = print_r($paymentParem, true);
                $log->response = $result['response'];
                $log->status = $result['status'];
                if ($log->save()) {
                    $CCPayment = true;
                }
                if ($result['status'] !== 'Done') {
                    $msg = substr($result['response'], 0, strlen($result['response']) - 1);
                    if ($msg == false) {
                        $msg = 'Your transaction was not Approved';
                    } else {
                        $msg = str_replace('|', '<br>', $msg);
                        $msg = str_replace('~', ': ', $msg);
                    }
                    exit(json_encode(['msgType' => 'ERR', 'msg' => $msg, 'ccPayment' => $CCPayment . ' - ' . $result['response']]));
                } else {
                    if (!empty($result['transactionId']) && $result['transactionId'] != 'undefined') {
                        $transId = $sunlineOrder->transaction_Id = $result['transactionId'];
                    }
                }
            } // end paytrace payment

            $cusResult = '';
            if ($sunlineOrder->save()) {
                if ($applyCredit == 'Yes') {
                    $custInfo->credit = 0;
                    $cusResult = $custInfo->save();
                }
                exit(json_encode(['msgType' => 'SUC', 'msg' => 'Payment has been processed', 'ccPayment' => $CCPayment, 'transaction_id' => $transId, 'cstmr' => json_encode($cusResult)]));
            } else {
                exit(json_encode(['msgType' => 'ERR', 'msg' => 'Something went wrong.']));
            }
        }
    }

    public function actionFileUpload() {
        if (Yii::$app->request->isAjax) {
            $model = new \app\modules\sunline\models\UploadForm();
            $model->load(Yii::$app->request->post());
            $model->pdfFile = UploadedFile::getInstances($model, 'pdfFile');
            $formVals = Yii::$app->request->post('OrderForm');
            $id = $formVals['_id'];
            $orderModel = GlobalFunction::findModel("app\common\models\SunlineOrder", $id);
            if (empty($orderModel->tinerary_file_name)) {
                $fn = 0;
            } else {
                $fn = $orderModel->tinerary_file_name[count($orderModel->tinerary_file_name) - 1];
                $fn = explode('.', $fn);
                $fn = substr($fn[0], strlen($fn[0]) - 1);
            }

            if (!empty($orderModel->tinerary_file_name)) {
                $tempArr = $orderModel->tinerary_file_name;
            } else {
                $tempArr = [];
            }
            $fnames = [];
            $fileName = "SL_" . $orderModel->order_id . "_" . str_replace(' ', '_', $orderModel->first_name) . '_I';
            foreach ($model->pdfFile as $value) {
                $Name = $value->name = $fileName . ( ++$fn) . "." . $model->pdfFile[0]->extension;
                array_push($tempArr, $Name);
                array_push($fnames, $Name);
            }
            //$model->fileName = $fileName; 
            //echo json_encode($model->pdfFile); 
            if ($model->upload()) {
                //$fileName = $fileName . "." . $model->pdfFile->extension;
//                if (!empty($orderModel->tinerary_file_name)) {
//                    $tempArr = $orderModel->tinerary_file_name;
//                    array_push($tempArr, $fileName);
//                    $orderModel->tinerary_file_name = $tempArr;
//                } else {
//                    $orderModel->tinerary_file_name = [$fileName];
//                }
                $orderModel->tinerary_file_name = $tempArr;
                $orderModel->save();
                exit(json_encode(['msgType' => 'SUC', 'fileName' => $fnames, 'msg' => 'Itinerary has been uploaded successfully.']));
            } else {
                $errors = $model->getErrors();
                exit(json_encode(['msgType' => 'ERR', 'msg' => $errors['pdfFile'][0]]));
            }
        }
    }

    public function actionSendItinerary() {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post("id");
            $orderModel = GlobalFunction::findModel("app\common\models\SunlineOrder", $id);
            if ($orderModel->status != '5') {
                exit(json_encode(['msgType' => 'ERR', 'msg' => 'Please do not skip the Invoice step.']));
            }
            $fileArr = $orderModel->tinerary_file_name;
            $files = [];
            foreach ($fileArr as $value) {
                $files[Url::base(true) . "/uploads/" . $value] = $value;
            }
            $fname = $orderModel->first_name;
//            $url = Url::base(true) . "/uploads/" . $fileName;
//            $files[$url] = $fileName;
//            $files[Url::base(true) . "/uploads/" . 'INV_T2_ali_1460557278.pdf'] = 'INV_T2_ali_1460557278.pdf';
//            $message = "Dear $fname,<br /><br />Please <a href='$url' download>click here</a> to download itienary.<br /><br />Thanks,";
            $message = "Dear $fname,<br /><br />Please look at the attachments to download itienary.<br /><br />Thanks,";
            $subject = 'Itienary - Sunline';
            $custInfo = GlobalFunction::findModel("\app\common\models\SunlineCustomer", ['customer_id' => $orderModel->customer_id]);
            $email = $custInfo->email;
            $response = GlobalFunction::sendMail(['emailTo' => $email, 'message' => $message, 'subject' => $subject, 'module' => 'sunline', 'files' => $files]);
            $orderModel->status = '6';
            $orderModel->save();
            exit(json_encode(['msgType' => 'SUC', 'msg' => 'Email has been sent.', 'mail' => json_encode($response)]));
        }
    }

    public function actionDeleteItinerary() {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post("id");
            $fileName = Yii::$app->request->post("file");
            $orderModel = GlobalFunction::findModel("app\common\models\SunlineOrder", $id);
            $fileArr = $orderModel->tinerary_file_name;
            $index = array_search($fileName, $fileArr);
            array_splice($fileArr, $index, 1);
            $orderModel->tinerary_file_name = $fileArr;
            if ($orderModel->save()) {
                $fileToRemove = "uploads/" . $fileName;
                unlink($fileToRemove);
                exit(json_encode(['msgType' => 'SUC', 'msg' => 'Itinerary has been deleted.', 'arr' => json_encode($fileArr)]));
            } else {
                exit(json_encode(['msgType' => 'ERR', 'msg' => 'Something went wrong.']));
            }
        }
    }

    public function actionChangeStatus() {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post("id");
            $status = Yii::$app->request->post("status");
            $sunlineOrder = GlobalFunction::findModel("\app\common\models\SunlineOrder", $id);
            $sunlineOrder->status = $status;
            if ($sunlineOrder->save()) {
                exit(json_encode(['msgType' => 'SUC']));
            } else {
                exit(json_encode(['msgType' => 'ERR', 'msg' => 'Something went wrong.']));
            }
        }
    }

    public function actionSMOrderPayment() {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post("id");
            $sMOrder = GlobalFunction::findModel("\app\common\models\SellmileageOrder", $id);
            $cstmr = GlobalFunction::findModel('app\common\models\SellmileageCustomer', ['customer_id' => $sMOrder->customer_id]);

            $sMOrder->status = 'Paid';
            $cstmr->is_paid = 'Y';
            if ($sMOrder->save()) {
                $cstmr->save();
                exit(json_encode(['msgType' => 'SUC']));
            } else {
                exit(json_encode(['msgType' => 'ERR', 'msg' => 'Something went wrong.']));
            }
        }
    }

    public function actionSMUserList() {
        if (Yii::$app->request->isAjax) {
            $program = Yii::$app->request->post("program");
            $data = GlobalFunction::getData("app\common\models\SellmileageOrder", ['status' => 'Paid', 'mileage_program' => $program]);

            if ($data) {
                $orders = [];
                foreach ($data as $value) {
                    $order['_id'] = $value['_id'];
                    $order['name'] = $value['first_name'] . ' ' . $value['last_name'];
                    $order['customer_id'] = $value['customer_id'];
                    $order['order_id'] = $value['order_id'];

                    array_push($orders, $order);
                }
                exit(json_encode($orders));
            } elseif (count($data) == 0) {
                exit(json_encode(['msgType' => 'ERR', 'msg' => 'No user orders found.']));
            } else {
                exit(json_encode(['msgType' => 'ERR', 'msg' => 'Something went wrong.']));
            }
        }
    }

    public function actionSMOrderDetail() {
        if (Yii::$app->request->isAjax) {
            $orderId = Yii::$app->request->post("id");
            $sMOrder = GlobalFunction::findModel("\app\common\models\SellmileageOrder", $orderId);
            if ($sMOrder) {
                $order['cost'] = $sMOrder->cost;
                $order['total_miles'] = $sMOrder->miles;
                $usedMiles = 0;
                if (is_array($sMOrder->used_miles)) {
                    foreach ($sMOrder->used_miles as $value) {
                        $usedMiles += $value['miles_used'];
                    }
                } else {
                    $usedMiles = $sMOrder->used_miles;
                }
                $order['miles'] = $sMOrder->miles - $usedMiles;
                exit(json_encode($order));
            } else {
                exit(json_encode(['msgType' => 'ERR', 'msg' => 'Something went wrong.']));
            }
        }
    }

    public function actionUseSMMiles() {
        if (Yii::$app->request->isAjax) {
            $SLorderId = Yii::$app->request->post("SLOid");
            $SMorderId = Yii::$app->request->post("SMOid");
            $useMiles = Yii::$app->request->post("miles");
            $smOrderModel = GlobalFunction::findModel("\app\common\models\SellmileageOrder", $SMorderId);
            $slOrderModel = GlobalFunction::findModel("\app\common\models\SunlineOrder", $SLorderId);
            if ($smOrderModel && $slOrderModel) {
                $usedMiles = 0;
                $usedMilesArr = $smOrderModel->used_miles;
                if (!empty($usedMilesArr)) {
                    foreach ($usedMilesArr as $value) {
                        $usedMiles += $value['miles_used'];
                    }
                }
                $available = $smOrderModel->miles - $usedMiles;
                if ($available >= $useMiles) {
                    $usedMiles = ['id' => new \MongoId(),
                        'sl_order_object_id' => $SLorderId,
                        'miles_used' => $useMiles,
                        'sl_order_id' => $slOrderModel->order_id,
                        'sl_customer_id' => $slOrderModel->customer_id,
                        'sl_first_name' => $slOrderModel->first_name,
                        'sl_last_name' => $slOrderModel->last_name,
                        'from' => $slOrderModel->ticket_from,
                        'to' => $slOrderModel->ticket_to,
                        'class' => $slOrderModel->ticket_class];
                    if (empty($smOrderModel->used_miles) || count($smOrderModel->used_miles) == 0) {
                        $smOrderModel->used_miles = array($usedMiles);
                    } else {
                        $allUsedMiles = $smOrderModel->used_miles;
                        array_push($allUsedMiles, $usedMiles);
                        $smOrderModel->used_miles = $allUsedMiles;
                    }
                    $result = $smOrderModel->save();
                    if ($result) {
                        $smCusModel = GlobalFunction::getData("app\common\models\SellmileageCustomer", ['customer_id' => $smOrderModel->customer_id]);
                        $customer = $smCusModel[0];
                        exit(json_encode(['msgType' => 'SUC', 'name' => $smOrderModel->first_name . ' ' . $smOrderModel->last_name, '_id' => $customer->_id, 'used_miles_id' => (string) $usedMiles['id'], 'total_miles' => $smOrderModel->miles, 'available_miles' => $available]));
                    } else {
                        exit(json_encode(['msgType' => 'ERR', 'msg' => 'Something went wrong.<br>' . json_encode($smOrderModel->getErrors())]));
                    }
                } else {
                    exit(json_encode(['msgType' => 'ERR', 'msg' => 'No more miles available to use.']));
                }
            } else {
                exit(json_encode(['msgType' => 'ERR', 'msg' => 'Something went wrong.']));
            }
        }
    }

    public function actionDeleteSMUsedMiles() {
        if (Yii::$app->request->isAjax) {
            $usedMilesId = Yii::$app->request->post("id");
            $smOrder = GlobalFunction::getData('app\common\models\SellmileageOrder', ['used_miles.id' => new \MongoId($usedMilesId)]);
            $order = $smOrder[0];
            //json_encode($order->used_miles).'<br>';
            $db = \app\common\models\SellmileageOrder::getDb();
            $result = $db->getCollection('sm_orders')->update(['used_miles.id' => new \MongoId($usedMilesId)], ['$pull' => ['used_miles' => ['id' => new \MongoId($usedMilesId)]]]);
            if ($result) {
                exit(json_encode(['msgType' => 'SUC', 'order' => $order->used_miles]));
            } else {
                exit(json_encode(['msgType' => 'ERR', 'msg' => 'Something went wrong.']));
            }
        }
    }

    public function actionGenerateInvoice() {
        if (Yii::$app->request->isAjax) {
            $orderId = Yii::$app->request->post("id");
            $mpdf = new mPDF;
            $orderData = GlobalFunction::findModel("app\common\models\SunlineOrder", $orderId);
            $content = $this->renderPartial("_invoice", ["data" => $orderData]);
            if ($_GET['q'] == 'q') {
                echo $content;
                exit;
            }
            $fileName = "INV_" . $orderData->order_id . "_" . $orderData->first_name . "_" . time() . ".pdf";
            $mpdf->WriteHTML($content);
            $orderData->invoice_name = $fileName;
            if ($orderData->save()) {
                $path = 'uploads/invoices/' . $fileName;
                $mpdf->Output($path, 'F');
                exit(json_encode(['msgType' => 'SUC', 'fileName' => $fileName, 'msg' => 'Invoice created & uploaded successfully.']));
            } else {
                exit(json_encode(['msgType' => 'ERR', 'msg' => "Something went wrong."]));
            }
        }
    }

    public function actionDeleteInvoice() {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post("id");
            $orderModel = GlobalFunction::findModel("app\common\models\SunlineOrder", $id);
            $fileName = $orderModel->invoice_name;
            $orderModel->invoice_name = '';
            if ($orderModel->save()) {
                $fileToRemove = "uploads/invoices/" . $fileName;
                unlink($fileToRemove);
                exit(json_encode(['msgType' => 'SUC', 'msg' => 'Invoice has been deleted.']));
            } else {
                exit(json_encode(['msgType' => 'ERR', 'msg' => 'Something went wrong.']));
            }
        }
    }

    public function actionSendInvoice() {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post("id");
            $orderModel = GlobalFunction::findModel("app\common\models\SunlineOrder", $id);
            if ($orderModel->status != '4') {
                exit(json_encode(['msgType' => 'ERR', 'msg' => 'Please do not skip the Payment step.']));
            }
            $fileName = $orderModel->invoice_name;
            $fname = $orderModel->first_name;
            $url = Url::base(true) . "/uploads/invoices/" . $fileName;
            $message = "Dear $fname,<br /><br />Please <a href='$url' download>click here</a> to download invoice.<br /><br />Thanks,";
            $subject = 'Invoice - Sunline';
            $custInfo = GlobalFunction::findModel("\app\common\models\SunlineCustomer", ['customer_id' => $orderModel->customer_id]);
            $email = $custInfo->email;
            GlobalFunction::sendMail(['emailTo' => $email, 'message' => $message, 'subject' => $subject, 'module' => 'sunline']);
            $orderModel->status = '5';
            $orderModel->save();
            exit(json_encode(['msgType' => 'SUC', 'msg' => 'Email has been sent.']));
        }
    }

    public function actionChangeSMStatus() {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post("id");
            $status = Yii::$app->request->post("status");
            $sunlineOrder = GlobalFunction::findModel("\app\common\models\SellmileageOrder", $id);
            $sunlineOrder->status = $status;
            if ($sunlineOrder->save()) {
                exit(json_encode(['msgType' => 'SUC']));
            } else {
                exit(json_encode(['msgType' => 'ERR', 'msg' => 'Something went wrong.']));
            }
        }
    }

    public function actionDisableCustomer() {
        $id = Yii::$app->request->post("id");
        if (MODULE == 'sunline') {
            $sunlineCustomer = GlobalFunction::findModel("\app\common\models\SunlineCustomer", $id);
            $sunlineCustomer->is_active = 'N';
            if ($sunlineCustomer->save()) {
                exit(json_encode(['msgType' => 'SUC', 'mdl' => MODULE, 'customer' => json_encode($sunlineCustomer->customer_id)]));
            } else {
                exit(json_encode(['msgType' => 'ERR', 'msg' => 'Something went wrong.']));
            }
        }
        if (MODULE == 'sellmileage') {
            $SmCustomer = GlobalFunction::findModel("\app\common\models\SellmileageCustomer", $id);
            $SmCustomer->is_active = 'N';
            if ($SmCustomer->save()) {
                exit(json_encode(['msgType' => 'SUC', 'mdl' => MODULE, 'customer' => json_encode($SmCustomer->customer_id)]));
            } else {
                exit(json_encode(['msgType' => 'ERR', 'msg' => 'Something went wrong.', 'error' => json_encode($SmCustomer->getErrors())]));
            }
        }
    }

    public function actionEnableCustomer() {
        $id = Yii::$app->request->post("id");
        if (MODULE == 'sunline') {
            $sunlineCustomer = GlobalFunction::findModel("\app\common\models\SunlineCustomer", $id);
            $sunlineCustomer->is_active = 'Y';
            if ($sunlineCustomer->save()) {
                exit(json_encode(['msgType' => 'SUC', 'mdl' => MODULE, 'customer' => json_encode($sunlineCustomer->customer_id)]));
            } else {
                exit(json_encode(['msgType' => 'ERR', 'msg' => 'Something went wrong.']));
            }
        }
        if (MODULE == 'sellmileage') {
            $SmCustomer = GlobalFunction::findModel("\app\common\models\SellmileageCustomer", $id);
            $SmCustomer->is_active = 'Y';
            if ($SmCustomer->save()) {
                exit(json_encode(['msgType' => 'SUC', 'mdl' => MODULE, 'customer' => json_encode($SmCustomer->customer_id)]));
            } else {
                exit(json_encode(['msgType' => 'ERR', 'msg' => 'Something went wrong.', 'error' => json_encode($SmCustomer->getErrors())]));
            }
        }
    }

    public function actionItineraryFromCheckmytrip() {
        $id = Yii::$app->request->post("id");
        $bookingReference = Yii::$app->request->post("CnfmNo");
        $orderModel = GlobalFunction::findModel("app\common\models\SunlineOrder", $id);

        $url = 'https://www.checkmytrip.com/cmt/apf/cmtng/index?LANGUAGE=GB&SITE=NCMTNCMT#pnr/retrieve/3H2GTM/Seddigh';
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', $url);
        $crawler->filter('a')->each(function ($node) {
            echo $node->text() . "<br>";
        });
//        $link = $crawler->selectLink('pdf')->link();
//        echo json_encode($link);
    }

}
