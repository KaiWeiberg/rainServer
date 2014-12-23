<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $firstname
 * @property string $surname
 * @property string $email
 * @property string $sms
 * @property string $language
 * @property string $role
 * @property integer $status
 * @property string $getinfo
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
   
    const cUSER = 'usr';
    const cADMIN = 'adm';
    const cDEVELOPER = 'dev';
    
    
    
    public $password;
    public $passwordRepeat;
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
//        $scenarios['insert'] = ['password', 'required'];

        return $scenarios;
    }
    
    
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
          
            ['password', 'required','on' => 'create'],

           // ['password', 'string', 'min'=>8, 'max'=>16, 'on' => 'insert,updatePassword'],
          
            [[ 'firstname', 'surname', 'email','language','role'], 'required'],
            
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'unique', 'message' => 'This email address has already been taken.'],
            ['email', 'string', 'max' => 255],
            
            [['firstname', 'surname'], 'string', 'max' => 45],
            [['sms', 'role'], 'string', 'max' => 32],           
            [['language', 'getinfo'], 'string', 'max' => 16],

            ['status', 'integer'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'firstname' => Yii::t('app', 'Firstname'),
            'surname' => Yii::t('app', 'Surname'),
            'email' => Yii::t('app', 'Email'),
            'sms' => Yii::t('app', 'Sms'),
            'language' => Yii::t('app', 'Language'),
            'role' => Yii::t('app', 'Role'),
            'status' => Yii::t('app', 'Status'),
            'getinfo' => Yii::t('app', 'Getinfo'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
   
     /**
     * @inheritdoc
     */
    public function getId()     // IdentityInterface 
    {
        return $this->getPrimaryKey();
    }
    
     /**
     * @inheritdoc
     */
    public static function findIdentity($id)     // IdentityInterface
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }
    
     /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)     // IdentityInterface
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    
     /**
     * @inheritdoc
     */
    public function getAuthKey()     // IdentityInterface
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)     // IdentityInterface
    {
        return $this->getAuthKey() === $authKey;
    }
    
     /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)     // IdentityInterface
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }
    
    /**
     * Generates password hash from password and sets it to the model
     * @param string $password
     *
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
   
    /**
     * Generates "remember me" authentication key
     *
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    */
    
    
     /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    
     public function beforeSave($insert)
    {
        if(isset($this->password)) 
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        
        if($this->isNewRecord)
            $this->auth_key = Yii::$app->security->generateRandomString();
        
        return parent::beforeSave($insert);
    }
        
    
}
