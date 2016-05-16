<?php

namespace app\common\models;

use Yii;
use yii\mongodb\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $user_name
 * @property string $password
 * @property string $user_role
 * @property string $first_name
 * @property string $last_name
 * @property string $auth_key
 */
class User extends ActiveRecord implements IdentityInterface {

    /**
     * @inheritdoc
     */
    const ROLE_ADMIN = 1;
    const ROLE_manager = 2;
    const ROLE_supervisor = 3;
    const ROLE_executive = 4;
    const ROLE_operator = 5;

    public static function collectionName() {
        return ['fourLink', 'users'];
    }

    public function attributes() {
        return [
            '_id',
            'user_id',
            'first_name',
            'last_name',
            'user_role',
            'email',
            'phone',
            'address',
            'report_to',
            'password',
            'profile_picture',
            'auth_key',
            'created',
            'created_by',
            'updated',
            'updated_by'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['_id', 'user_id', 'first_name', 'last_name', 'email', 'phone', 'address', 'report_to', 'password', 'user_role', 'profile_picture', 'auth_key'], 'safe'],
            [['email', 'password', 'first_name', 'auth_key'], 'string', 'max' => 50],
            ['created', 'default', 'value' => date("Y-m-d H:i:s"), 'on' => 'create'],
            ['created_by', 'default', 'value' => Yii::$app->user->identity->email, 'on' => 'create'],
                //['updated', 'default', 'value' => date("Y-m-d H:i:s"), 'on' => 'update'],
                //['updated_by', 'default', 'value' => Yii::$app->user->identity->email, 'on' => 'update'],
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

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email Address',
            'password' => 'Password',
            'user_role' => 'User Type',
            'user_role' => 'Role',
            'report_to' => 'Report To',
            'auth_key' => 'Auth Key',
        ];
    }

    public function getAuthKey() {
        return $this->auth_key;
    }

    public function getId() {
        return $this->_id;
    }

    public function getUserType() {
        return $this->user_role;
    }

    public function getUserRole() {
        return $this->user_role;
    }

    public function validateAuthKey($authKey) {
        return $this->auth_key === $authKey;
    }

    public static function findIdentity($id) {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        throw new \yii\base\NotSupportedException();
    }

    public static function findByEmail($email) {
        return self::findOne(['email' => $email]);
    }

    public function validatePassword($password) {
        return $this->password === md5($password);
    }

    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function getListing($params) {
        $pagination = (isset($params['pagination']) ? $params['pagination'] : '');
        $whereParams = (isset($params['whereParams']) ? $params['whereParams'] : '');

        $query = User::find();
        $query->select(['_id', 'user_id', 'first_name', 'last_name', 'email', 'phone', 'address', 'report_to', 'password', 'user_role']);

        if ($whereParams)
            $query->where($whereParams);

        if ($pagination)
            $query->offset($pagination->offset)->limit($pagination->limit);

        return $query->all();
    }

    public function getCount() {
        $query = User::find();
        return $query->count();
    }

    public function findModel($id) {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
