<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\components\AccessRule;
use app\common\models\Customer;
use yii\data\Pagination;
use yii\web\Controller;
use app\models\CustomerForm;
use yii\widgets\ActiveForm;
use app\components\GlobalFunction;
use yii\web\UploadedFile;

/**
 * Description of CustomerController
 *
 * @author Muhammad Asif
 */
class CustomersController extends Controller {

    const className = 'app\common\models\Customer';

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'detail', 'document-upload', 'remove-doc'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

// end function

    public function actionIndex() {
        $className = self::className;
        $nameS = $sort = '';
        $model = new CustomerForm();
        //$model->scenario = 'create';
        $modelu = new CustomerForm();
        //$modelu->scenario = 'update';
        if (Yii::$app->request->post()) {                 // create Plan
            $model->load(Yii::$app->request->post());
            $retData = $model->createOrUpdate(Yii::$app->request->post('CustomerForm'));
            if ($retData['msgType'] == 'ERR') {
                ;
            } else {
                $model = new CustomerForm();
            }
        }

        if (Yii::$app->request->get('sort')) {
            $sort = Yii::$app->request->get('sort');
        }
        if (Yii::$app->request->get('nameS')) {
            $nameS = Yii::$app->request->get('nameS');
        }

        $count = GlobalFunction::getCount(['className' => $className, 'whereParams' => '', 'nameS' => $nameS]);
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => Yii::$app->params['pageSize']]);
        $data = GlobalFunction::getListing(['className' => $className, 'pagination' => $pagination, 'whereParams' => '', 'nameS' => $nameS, 'sort' => $sort, 'selectParams' => ['_id', 'customer_id', 'customer_acc', 'first_name', 'account_no', 'address', 'phone', 'sales_agent', 'agent_phone']]);

        return $this->render('index', [
                    'data' => $data,
                    'model' => $model,
                    'modelu' => $modelu,
                    'pagination' => $pagination,
                    'agentList' => GlobalFunction::getAgentList(),
        ]);
    }

// end index

    public function actionUpdate() {
        $model = new CustomerForm();
        //$model->scenario = 'update';
        if (Yii::$app->request->post()) {                 // create Plan
            $model->load(Yii::$app->request->post());
            $retData = $model->createOrUpdate(Yii::$app->request->post('CustomerForm'));

            if ($retData['msgType'] == 'ERR') {
                exit(json_encode(['msgType' => 'ERR', 'msgArr' => $retData['msgArr']]));
            } else {
                exit(json_encode(['msgType' => 'SUC']));
            }
        }
    }

    public function actionDetail($id) {

        $customer = Customer::findOne($id);
        if (count($customer) > 0) {
            $model = new CustomerForm();
            $model->scenario = 'detail';
            $model->attributes = $customer->attributes;
            $documentModel = new \app\models\UploadForm();
            $documentModel->scenario = 'create';

            if (Yii::$app->request->post()) {                 // Detail Update
                $model->load(Yii::$app->request->post());
                $retData = $model->createOrUpdate(Yii::$app->request->post('CustomerForm'));

                if ($retData['msgType'] == 'ERR') {
                    exit(json_encode(['msgType' => 'ERR', 'msgArr' => $retData['msgArr']]));
                } else {
                    ; //exit(json_encode(['msgType' => 'SUC']));
                }
            }

            return $this->render('detail', [
                        'model' => $model,
                        'documentModel' => $documentModel,
                        'documents' => $customer->documents,
                        'countryList' => GlobalFunction::getCountries(),
                        'agentList' => GlobalFunction::getAgentList(),
            ]);
        } else {
            return $this->render('detail', [
                        'errmsg' => 'In valid Customer ID',
            ]);
        }
    }

    // upload documents
    public function actionDocumentUpload($id) {
        if (Yii::$app->request->post()) {
            $customer = Customer::findOne($id);
            if (count($customer) > 0) {
                $model = new \app\models\UploadForm();
                $model->scenario = 'create';
                $model->load(Yii::$app->request->post());
                $model->pdfFile = UploadedFile::getInstance($model, 'pdfFile');
                $did = new \MongoId();

                $model->pdfFile->name = $did . '-' . str_replace(' ', '_', $model->fileName) . "." . $model->pdfFile->extension;
                if ($model->upload()) {
                    $db = $customer->getDb();
                    $document = ['id' => $did,
                        'name' => $model->fileName,
                        'file' => $model->pdfFile->name,
                        'date' => $model->created,
                        'uploaded_by' => $model->created_by];
                    $result = $db->getCollection('customer')->update(['_id' => new \MongoId($id)], ['$push' => ['documents' => $document]]);
                    if ($result) {
                        exit(json_encode(['msgType' => 'SUC', 'msg' => 'File is Successfully Uploaded', 'document' => $document]));
                    } else {
                        exit(json_encode(['msgType' => 'ERR', 'msg' => 'Some thing went wrong', 'result' => json_encode($result), 'file' => $model->attributes]));
                    }
                } else {
                    exit(json_encode(['msgType' => 'ERR', 'msg' => 'Some thing went wrong', 'result' => json_encode($model->errors), 'file' => $model->attributes]));
                }
            }
        }
    }

    // delete the document of customer
    public function actionRemoveDoc() {
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            $customer = Yii::$app->request->post('customerId');
            $docId = Yii::$app->request->post('id');

            $db = Customer::getDb();
            $data = $db->getCollection('customer')->find(['documents.id' => new \MongoId($docId)], ['documents' => ['$elemMatch' => ['id' => new \MongoId($docId)]]]);
            if (count($data) > 0) {
                foreach ($data as $value) {
                    $documents = $value['documents'][0];
                }
                $file = $documents['file'];
                $result = $db->getCollection('customer')->update(['documents.id' => new \MongoId($docId)], ['$pull' => ['documents' => ['id' => new \MongoId($docId)]]]);
                if ($result) {
                    unlink('uploads/documents/' . $file);
                    exit(json_encode(['msgType' => 'SUC', 'msg' => 'File is Successfully removed', 'file' => $documents['name']]));
                }
            } else {
                exit(json_encode(['msgType' => 'SUC', 'msg' => 'File not found']));
            }
        }
    }

// end class
}
