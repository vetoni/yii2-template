<?php
namespace common\modules\user\models;

use common\modules\user\traits\ModuleTrait;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * @class User
 * @package common\models
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password
 * @property array $roles
 */
class User extends ActiveRecord implements IdentityInterface
{
    use ModuleTrait;

    /**
     * @var
     */
    public $password;

    /**
     * Inactive user
     */
    const STATUS_INACTIVE = 0;
    /**
     * Active user
     */
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return ArrayHelper::merge($scenarios, [
            'create' => ['username', 'email', 'password', 'status', 'roles'],
            'register' => ['username', 'email', 'password', 'status', 'roles'],
            'update' => ['username', 'email', 'password', 'status', 'roles'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['username', 'email'], 'unique'],
            ['username', 'trim'],
            [['roles'], 'required', 'on' => ['create', 'update', 'register']],

            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],

            ['password', 'required', 'on' => 'create'],
            ['password', 'string', 'min' => 6],
            ['roles', 'each', 'rule' => ['string']],

            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', 'ID'),
            'username' => Yii::t('user', 'Логин'),
            'email' => Yii::t('user', 'Email'),
            'created_at' => Yii::t('user', 'Дата регистрации'),
            'status' => Yii::t('user', 'Статус'),
            'roles' => Yii::t('user', 'Роли'),
            'password' => Yii::t('user', 'Пароль'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $name
     * @return static|ActiveRecord
     */
    public static function findIdentityByNameOrEmail($name)
    {
        return static::find()
            ->andWhere(['OR', ['username' => $name], ['email' => $name]])
            ->andWhere(['status' => static::STATUS_ACTIVE])
            ->one();
    }

    /**
     * @param $name
     * @return static
     */
    public static function findIdentityByName($name)
    {
        return static::findOne(['username' => $name, 'status' => static::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->password) {
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        }
        return parent::beforeSave($insert);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->assignRoles($this->roles);
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @return bool Whether the user is an admin or not.
     */
    public function getIsAdmin()
    {
        $userRoles = $this->getRoles();
        return $this->module->adminRoles && array_intersect($userRoles, $this->module->adminRoles);
    }

    /**
     * @param string[] $roles
     */
    public function assignRoles($roles)
    {
        \Yii::$app->authManager->revokeAll($this->id);
        foreach ($roles as $role) {
            $role = \Yii::$app->authManager->getRole($role);
            \Yii::$app->authManager->assign($role, $this->id);
        }
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        $authManager = \Yii::$app->getAuthManager();
        return array_keys($authManager->getRolesByUser($this->id));
    }

    /**
     * @param $value
     */
    public function setRoles($value)
    {
        $this->roles = $value;
    }

    /**
     * @return string
     */
    public function getRoleListAsString()
    {
        return implode(', ', $this->getRoles());
    }

    /**
     * @return string
     */
    public function getStatusText()
    {
        $options = $this->getStatusOptions();
        return $options[$this->status];
    }

    /**
     * @return array
     */
    public static function getStatusOptions()
    {
        return [
            static::STATUS_INACTIVE => Yii::t('user', 'Не активен'),
            static::STATUS_ACTIVE => Yii::t('user', 'Активен'),
        ];
    }
}
