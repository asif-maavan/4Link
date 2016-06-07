<?php

namespace app\controllers;

use Yii;
use app\common\models\User;
use yii\data\Pagination;
use yii\web\Controller;
use app\models\UserForm;
use yii\widgets\ActiveForm;
use app\components\GlobalFunction;
use yii\web\UploadedFile;

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

    public function actionMyAccount() {
        $model = new UserForm();
        $model->scenario = 'profile';
        $user = User::findOne(Yii::$app->user->identity->_id);
        $model->attributes = $user->attributes;
        $profilePic = $user->profile_picture;

        if (Yii::$app->request->post()) {
            $model->profile_picture = UploadedFile::getInstance($model, 'profile_picture');
            //echo \GuzzleHttp\json_encode($model->profile_picture);
            $retData = $model->update(Yii::$app->request->post('UserForm'));
            if ($retData['msgType'] == 'ERR') {
                $model->errors = $retData['msgArr'];
//                exit(json_encode(['msgType' => 'ERR', 'msgArr' => $retData['msgArr']]));
            } else {
                $this->redirect(Yii::$app->urlManager->createUrl("user/my-account"));
                //exit();
            }
        }

        return $this->render('myAccount', [
                    'model' => $model,
                    'profilePic' => $profilePic,
                    'roleList' => GlobalFunction::getUserRoles(),
        ]);
    }

// end MyAccount
    // user forgot password
    public function actionForgot() {
        /** @var \amnah\yii2\user\models\forms\ForgotForm $model */
        // load post data and send email
        $this->layout = "loginLayout";
        $model = new UserForm();
        $model->scenario = 'forgot';
        if ($model->load(Yii::$app->request->post())) {
            // set flash (which will show on the current page)              // set flash (which will show on the current page)  
            if ($model->validate()) {
                $string = md5(time() . rand(1000, 9999));
                $user = User::findOne(['email' => $model->email]);
                $user->forgot_password_token = $string;
                if ($user->save()) {
                    $email = $model->email;
                    $subject = 'Password Reset';
                    $url = \yii\helpers\Url::base(true) . '/user/reset?token=' . $string;
                    $message = "Dear $model->first_name $model->last_name,<br /><br />Please <a href='$url'>click here</a> to reset your password.<br /><br />Thank you for Using 4link";
                    $result = GlobalFunction::sendMail(['emailTo' => $email, 'message' => $message, 'subject' => $subject]);
                    Yii::$app->session->setFlash("Forgot-success", Yii::t("app", "Instructions to reset your password have been sent"));
//                echo json_encode($result);
                }
            }
//            else {
//                var_dump($model->getErrors()); //or print_r($errors)
//                exit;
//            }
        }
        return $this->render("forgot", [
                    'model' => $model,
        ]);
    }

    // user reset password after email athentication
    public function actionReset() {
        $this->layout = "loginLayout";
        $token = Yii::$app->request->get('token');
        $model = $msg = '';
        if ($token != '') {
            $user = User::findOne(['forgot_password_token' => $token]);
            if (empty($user)) {
                Yii::$app->session->setFlash("Forgot-error", Yii::t("app", "Your link has expired."));
            } else {
                $model = new UserForm();
                $model->scenario = 'reset';
                $model->attributes = $user->attributes;
                if ($model->load(Yii::$app->request->post())) {
                    $model->email = $user->email;
                    $result = $model->resetPassword();
                    if($result['msgType'] == 'ERR'){
                        ;
                    }else{
                        Yii::$app->session->setFlash("Forgot-success", Yii::t("app", "Your password has been successfully changed"));
                    }
                }
            }
        } else {
            Yii::$app->session->setFlash("Forgot-error", Yii::t("app", "Invalid request."));
        }

        return $this->render("reset", [
                    'model' => $model,
        ]);
    }
    
    public function actionResetValidation() {
        $model = new UserForm();
        $model->scenario = 'reset';
        //$params['UserForm'] = Yii::$app->request->post('UserForm')[$id];
        //echo json_encode($params);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {//echo json_encode($model->attributes);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

// end class
}
