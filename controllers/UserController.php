<?php

namespace app\controllers;

use Yii;
use app\common\models\User;
use yii\data\Pagination;
use yii\web\Controller;
use app\models\UserForm;
use yii\widgets\ActiveForm;
use app\components\GlobalFunction;

class UserController extends AppController {

    public function actionIndex() {
        $className = 'app\common\models\user';
        $whereParams = $nameS = $sort = $mileageProgram = $paid = '';
        $model = new UserForm();
        $model->scenario = 'create';
        $modelu = new UserForm();
        $modelu->scenario = 'update';
        if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            if (Yii::$app->request->post()) {                 // create user
                $retData = $model->create(Yii::$app->request->post('UserForm'));
                if ($retData['msgType'] == 'ERR') {
                    ;
                } else {
                    $model = new UserForm();
                    $model->scenario = 'create';
                }
            }

            if (Yii::$app->request->get()) {
                $sort = Yii::$app->request->get('sort');
            }
            $count = GlobalFunction::getCount(['className' => $className, 'whereParams' => '', 'nameS' => '']);
            $pagination = new Pagination(['totalCount' => $count, 'pageSize' => Yii::$app->params['pageSize']]);
            $data = GlobalFunction::getListing(['className' => $className, 'pagination' => $pagination, 'whereParams' => '', 'nameS' => '', 'sort' => $sort, 'selectParams' => ['_id', 'user_id', 'first_name', 'last_name', 'email', 'phone', 'address', 'report_to', 'password', 'user_role']]);

            return $this->render('index', [
                        'data' => $data,
                        'model' => $model,
                        'modelu' => $modelu,
                        'pagination' => $pagination,
                        'roleList' => GlobalFunction::getUserRoles(),
            ]);
        } else {
            $data = User::getListing(['whereParams' => ['email' => Yii::$app->user->identity->email]]);
            return $this->render('index', [
                        'data' => $data,
                        'roleList' => GlobalFunction::getUserRoles(),
            ]);
        }
    }

    public function actionCreate() {
        $model = new UserForm();
        $model->scenario = 'create';
        $retData = $model->create(Yii::$app->request->post('UserForm'));

        if ($retData['msgType'] == 'ERR') {
            exit(json_encode(['msgType' => 'ERR', 'msgArr' => $retData['msgArr']]));
        } else {
            //exit(json_encode(['msgType' => 'SUC', 'id' => (string)$retData['id']]));
            $this->redirect(Yii::$app->urlManager->createUrl("user/"));
        }
    }

    public function actionUpdate() {
        $model = new UserForm();
        $model->scenario = 'update';
        $retData = $model->update(Yii::$app->request->post('UserForm'));

        if ($retData['msgType'] == 'ERR') {
            exit(json_encode(['msgType' => 'ERR', 'msgArr' => $retData['msgArr']]));
        } else {
            exit(json_encode(['msgType' => 'SUC']));
        }
    }

    public function actionDelete() {
        $id = Yii::$app->request->post('id');
        $model = new User();
        $model->findModel($id)->delete();
        exit(json_encode(['msgType' => 'SUC']));
    }

    public function actionCreateValidation() {
        $model = new UserForm();
        $model->scenario = 'create';

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {//echo json_encode($model->attributes);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    public function actionUpdateValidation() {
        $model = new UserForm();
        $model->scenario = 'update';
        //$params['UserForm'] = Yii::$app->request->post('UserForm')[$id];
        //echo json_encode($params);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {//echo json_encode($model->attributes);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    public function actionGetReportToList() {
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            $list = GlobalFunction::getReportToList(Yii::$app->request->post('_id'), Yii::$app->request->post('role'));
            exit(json_encode(['msgType' => 'SUC', 'list' => $list]));
        }
    }

}
