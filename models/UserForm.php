<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\common\models\User;
use yii\web\UploadedFile;

/**
 * LoginForm is the model behind the login form.
 */
class UserForm extends Model {

    public $_id;
    public $user_id;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $confirm_password;
    public $user_role;
    public $phone;
    public $address;
    public $report_to;
    public $profile_picture;

    public function rules() {
        return [
            // email and password are both required
            [['first_name', 'user_role'], 'required', 'on' => 'default'],
            [['first_name', 'last_name', 'email', 'address', 'phone', 'user_role', 'report_to', 'password', 'confirm_password'], 'required', 'on' => 'create'],
            [['first_name', 'last_name', 'address', 'phone', 'user_role', 'report_to'], 'required', 'on' => 'update'],
            [['first_name', 'last_name', 'address', 'phone'], 'required', 'on' => 'profile'],
            ['email', 'required', 'on' => 'forgot'],
            [['first_name', 'last_name', 'user_role'], 'safe', 'on' => 'forgot'],
            [['password', 'confirm_password'], 'required', 'on' => 'reset'],
            [['first_name', 'last_name', 'email', 'user_role'], 'safe', 'on' => 'reset'],
            [['_id', 'user_id', 'email', 'password', 'confirm_password', 'user_role', 'profile_picture'], 'safe'],
            ['password', 'string', 'min' => 6],
            ['confirm_password', 'validatePassword'],
            ['profile_picture', 'file', 'extensions' => 'gif, jpg, png'],
            ['user_role', 'in', 'range' => [User::ROLE_ADMIN, User::ROLE_operator, User::ROLE_manager, User::ROLE_supervisor, User::ROLE_executive]],
            ['email', 'email'],
            ['confirm_password', 'required', 'when' => function($model) {
                    return (isset($model->password) && !empty($model->password));
                }, 'enableClientValidation' => false],
            ['email', 'validateEmail'],
            [['first_name', 'last_name'], 'match', 'pattern' => '/^[a-zA-z]*$/','message'=>Yii::t('app','Use Alphabets only.')],
            [[ 'phone'], 'number'],
        ];
    }

    public function validateEmail($attribute, $params) {
        if ($this->scenario == 'forgot') {
            $whereParams = ['email' => $this->email];
            //$models = \app\components\GlobalFunction::getListing(['className' => 'app\common\models\User', 'whereParams' => $whereParams, 'selectParams' => ['index_no']]);
            $models = User::findOne($whereParams);
            if (count($models) == 0) {
                $this->addError($attribute, 'Email not found');
            } else {
                $this->attributes = $models->attributes;
            }
        } else {
            $whereParams = ['and', ['not', '_id', new \MongoId($this->_id)], ['email' => $this->email]];
            $models = \app\components\GlobalFunction::getListing(['className' => 'app\common\models\User', 'whereParams' => $whereParams, 'selectParams' => ['index_no']]);
            if (count($models) > 0) {
                //echo count($models);
                $this->addError($attribute, 'This email is already taken');
            }
        }
    }

    public function validatePassword($attribute, $params) {
        if ($this->password !== $this->confirm_password) {
            $this->addError($attribute, 'Password mismatch found');
            return;
        }
    }

    public function create($postParams) {
        $this->scenario = 'create';
        $this->attributes = $postParams;
        if ($this->validate()) {
            $user = new User();
            $user->scenario = 'create';
            $user->attributes = $postParams;
            $user->user_role = intval($user->user_role);
            $user->password = md5($postParams['password']);
            $user->generateAuthKey();
            $data = $user::find()->select(['user_id'])->orderBy(['_id' => SORT_DESC])->one();
            if (!empty($data)) {
                $tmpStr = $data->user_id;
                $tmpStr++;
            } else {
                $tmpStr = 1;
            }
            $user->user_id = $tmpStr;
            $user->report_to = ['_id' => $user->report_to, 'name' => \app\components\GlobalFunction::getReportToList($user->_id, $user->user_role)[$user->report_to]];
            if ($user->save()) {
                return ['msgType' => 'SUC', 'id' => $user->_id];
            } else {
                $errors = $user->getErrors();
                return ['msgType' => 'ERR', 'msgArr' => $errors];
            }
        } else {
            $errors = $this->getErrors();
            return ['msgType' => 'ERR', 'msgArr' => $errors];
        }
    }

    public function update($postParams) {
        unset($postParams['profile_picture']);
        $this->attributes = $postParams;
        if ($this->scenario == 'profile') {
            $id = Yii::$app->user->identity->_id;
        } else {
            $id = $postParams['_id'];
            unset($postParams['_id']);
        }

        if ($id == Yii::$app->user->identity->_id)
            $this->user_role = Yii::$app->user->identity->user_role;
        if ($this->validate()) {

            $user = User::findModel($id);

            if ($this->profile_picture) {
                if (!empty($user->profile_picture)) {
                    unlink('uploads/' . $user->profile_picture);
                }
                $fileName = $id . '-' . $this->profile_picture->baseName . '.' . $this->profile_picture->extension;
                $this->profile_picture->saveAs('uploads/' . $fileName);
                $user->profile_picture = $fileName;
            }

            $user->attributes = $postParams;
            $user->user_role = intval($user->user_role);
            if (isset($postParams['report_to']))
                $user->report_to = ['_id' => $user->report_to, 'name' => \app\components\GlobalFunction::getReportToList($user->_id, $user->user_role)[$user->report_to]];

            if (!empty($postParams['password'])) {
                $user->password = md5($postParams['password']);
            } else {
                unset($user->password);
            }

            if ($user->save()) {
                return ['msgType' => 'SUC'];
            } else {
                $errors = $user->getErrors();
                return ['msgType' => 'ERR', 'msgArr' => $errors];
            }
        } else {
            $errors = $this->getErrors();
            return ['msgType' => 'ERR', 'msgArr' => $errors];
        }
    }

    // reset user password
    public function resetPassword() {
        if ($this->validate()) {
            $user = User::findOne(['email' => $this->email]);
            if ($user) {
                $user->password = md5($this->password);

                if ($user->save()) {
                    $user->forgot_password_token = '';
                    $user->save();
                    return ['msgType' => 'SUC'];
                } else {
                    $this->errors = $errors = $user->getErrors();
                    return ['msgType' => 'ERR', 'msgArr' => $errors];
                }
            } else {
                return ['msgType' => 'ERR', 'msgArr' => 'user not found'];
            }
        }
        return ['msgType' => 'ERR', 'msgArr' => $this->getErrors()];
    }

// end class
}
