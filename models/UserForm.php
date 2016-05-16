<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\common\models\User;

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
            [['first_name', 'user_role'], 'required'],
            [['first_name', 'last_name', 'email', 'address', 'phone', 'user_role', 'report_to', 'password', 'confirm_password'], 'required', 'on' => 'create'],
            [['first_name', 'last_name', 'address', 'phone', 'user_role', 'report_to'], 'required', 'on' => 'update'],
            [['_id', 'user_id', 'email', 'password', 'confirm_password'], 'safe'],
            ['password', 'string', 'min' => 6],
            ['confirm_password', 'validatePassword'],
            ['user_role', 'in', 'range' => [User::ROLE_ADMIN, User::ROLE_operator,  User::ROLE_manager, User::ROLE_supervisor, User::ROLE_executive]],
            ['email', 'email', 'on' => 'create'],
            ['email', 'unique', 'targetClass' => 'app\common\models\User', 'message' => 'This email address has already been taken.', 'on' => 'create'],
        ];
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
        $this->attributes = $postParams;
        $id = $postParams['_id'];
        if ($id == Yii::$app->user->identity->_id)
            $this->user_role = Yii::$app->user->identity->user_role;
        if ($this->validate()) {

            $user = User::findModel($id);
            //$email = $user->email;
            $user->attributes = $postParams;
            $user->user_role = intval($user->user_role);
            $user->_id = new \MongoId($postParams['_id']);
            //$user->email = $email;
            if (isset($postParams['password']))
                $user->password = md5($postParams['password']);
            //$model->scenario = 'update';
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

}
