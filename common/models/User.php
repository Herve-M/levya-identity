<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\log\Logger;
use yii\helpers\VarDumper;

use common\helpers\IPHelper;
use common\helpers\PasswordHelper;
use common\helpers\LDAPHelper;


/**
 * This is the model class for table "USER".
 *
 * @property string $USER_ID
 * @property string $USER_LASTNAME
 * @property string $USER_FORNAME
 * @property string $USER_MAIL
 * @property string $USER_MAIL_PROJECT 
 * @property string $USER_NICKNAME
 * @property string $USER_PASSWORD
 * @property string $USER_ADDRESS
 * @property string $USER_PHONE
 * @property string $USER_SECRETKEY
 * @property string $USER_CREATIONDATE
 * @property string $USER_CREATIONIP
 * @property string $USER_REGISTRATIONDATE
 * @property string $USER_REGISTRATIONIP
 * @property string $USER_UPDATEDATE
 * @property string $USER_AUTHKEY
 * @property integer $USERSTATE_USERSTATE_ID
 * @property string $USER_LDAPUID
 * @property integer $COUNTRY_CountryId
 * @property double $USER_LONGITUDE
 * @property double $USER_LATITUDE
 * @property integer $USER_ISDELETED 
 *
 * @property ACTIONHISTORY[] $r_ActionHistories
 * @property BELONG[] $r_Belongs
 * @property DONATION[] $r_Donations
 * @property SOCIALACCOUNT[] $r_SocialAccounts
 * @property TOKEN[] $r_Tokens
 * @property Country $r_Country
 * @property USERSTATE $r_UserState
 * @property WORK[] $r_Works
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /** @var string Plain password. Used for model validation. */
    public $TMP_PASSWORD;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'USER';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //Required
            [['USER_MAIL', 'USER_NICKNAME', 'USER_PASSWORD', 'USER_SECRETKEY', 'USER_CREATIONDATE', 'USER_REGISTRATIONDATE', 'USER_REGISTRATIONIP', 'USERSTATE_USERSTATE_ID', 'USER_LDAPUID'], 'required', 'on' => 'user_register'],
            [['USER_LASTNAME','USER_FORNAME', 'USER_MAIL', 'USER_MAIL_PROJECT', 'USER_NICKNAME', 'USER_PASSWORD', 'USER_ADDRESS', 'USER_PHONE', 'USER_SECRETKEY', 'USER_CREATIONDATE', 'USER_REGISTRATIONDATE', 'USER_REGISTRATIONIP', 'USERSTATE_USERSTATE_ID', 'USER_LDAPUID', 'COUNTRY_CountryId', 'USER_LONGITUDE', 'USER_LATITUDE'], 'required', 'on' => 'user_AsMember_register'],
            
            //USER_NICKNAME
            [['USER_NICKNAME'], 'unique'],
            [['USER_NICKNAME'], 'match', 'pattern' => '/^[\w]{3,15}$/'],
            
            //USER_EMAIL
            [['USER_MAIL', 'USER_MAIL_PROJECT'], 'string', 'max' => 254],
            [['USER_MAIL', 'USER_MAIL_PROJECT'], 'email'],
            [['USER_MAIL', 'USER_MAIL_PROJECT'], 'unique'],
            
            //USER DATE
            [['USER_CREATIONDATE', 'USER_REGISTRATIONDATE', 'USER_UPDATEDATE'], 'date'],
            
            //
            [['USER_LDAPUID'], 'string', 'max' => 100],
            [['USER_LDAPUID'], 'unique'],
            
            //
            [['USER_AUTHKEY'], 'string', 'max' => 32],
            [['USER_AUTHKEY'], 'unique'],
            
            //
            [['USER_SECRETKEY'], 'unique'],
            
            //
            [['USER_LASTNAME', 'USER_FORNAME', 'USER_NICKNAME', 'USER_SECRETKEY'], 'string', 'max' => 80],
            [['USERSTATE_USERSTATE_ID', 'COUNTRY_CountryId'], 'integer'],
            [['USER_ADDRESS'], 'string'],
            [['USER_PASSWORD'], 'string', 'max' => 255],
            [['USER_PHONE'], 'string', 'max' => 20],
            [['USER_REGISTRATIONIP'], 'string', 'max' => 16],
            
            //SAFE
            [['USER_MAIL', 'USER_MAIL_PROJECT', 'USER_NICKNAME','USER_LASTNAME','USER_FORNAME','USER_ADDRESS', 'USER_PHONE','COUNTRY_CountryId' ], 'safe']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function fields() {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['USER_AUTHKEY'], $fields['USER_SECRETKEY'], $fields['USER_PASSWORD']);

        return $fields;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'USER_ID' => Yii::t('app/user', 'User  ID'),
            'USER_LASTNAME' => Yii::t('app/user', 'User  Lastname'),
            'USER_FORNAME' => Yii::t('app/user', 'User  Forname'),
            'USER_MAIL' => Yii::t('app/user', 'User  Mail'),
            'USER_MAIL_PROJECT' => Yii::t('app/user', 'User Project Mail'),
            'USER_NICKNAME' => Yii::t('app/user', 'User  Nickname'),
            'USER_PASSWORD' => Yii::t('app/user', 'User  Password'),
            'USER_ADDRESS' => Yii::t('app/user', 'User  Address'),
            'USER_PHONE' => Yii::t('app/user', 'User  Phone'),
            'USER_SECRETKEY' => Yii::t('app/user', 'User  Secretkey'),
            'USER_CREATIONDATE' => Yii::t('app/user', 'User  Creationdate'),
            'USER_REGISTRATIONDATE' => Yii::t('app/user', 'User  Registrationdate'),
            'USER_REGISTRATIONIP' => Yii::t('app/user', 'User  Registrationip'),
            'USER_UPDATEDATE' => Yii::t('app/user', 'User  Updatedate'),
            'USER_AUTHKEY' => Yii::t('app/user', 'User  Authkey'),
            'USERSTATE_USERSTATE_ID' => Yii::t('app/user', 'Userstate  Userstate  ID'),
            'USER_LDAPUID' => Yii::t('app/user', 'User  Ldapuid'),
            'r_Country' => Yii::t('app/user', 'Country'),
            'USER_LONGITUDE' => Yii::t('app/user', 'User Longitude'), 
            'USER_LATITUDE' => Yii::t('app/user', 'User Latitude'),
            'USER_ISDELETED' => Yii::t('app/user', 'User Isdeleted'),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'user_register' => ['USER_MAIL', 'USER_NICKNAME', 'TMP_PASSWORD','!USER_PASSWORD', '!USER_SECRETKEY', '!USERSTATE_USERSTATE_ID', '!USER_LDAPUID'],
            'user_AsMember_register' => ['USER_LASTNAME','USER_FORNAME', 'USER_MAIL', 'USER_MAIL_PROJECT', 'USER_NICKNAME', '!USER_PASSWORD', 'USER_ADDRESS', 'USER_PHONE', '!USER_SECRETKEY', '!USERSTATE_USERSTATE_ID', '!USER_LDAPUID', 'COUNTRY_CountryId', 'USER_LONGITUDE', 'USER_LATITUDE'], //NEED BETTER DB data
            'user_update'   => ['USER_NICKNAME', 'USER_MAIL', 'USER_PASSWORD'],
            'user_AsMember_update' => ['USER_NICKNAME', 'USER_MAIL' , 'USER_MAIL_PROJECT', 'USER_PASSWORD'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'USER_CREATIONDATE',
                'updatedAtAttribute' => 'USER_UPDATEDATE',
                'value' =>  new Expression('NOW()')
            ],
        ];
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->USER_AUTHKEY = Yii::$app->getSecurity()->generateRandomString();
                $this->USER_MAIL = strtolower($this->USER_MAIL);
                $this->USER_CREATIONIP = IPHelper::IPtoBin(Yii::$app->request->userIP);
                $this->USER_PASSWORD = PasswordHelper::hash($this->TMP_PASSWORD);
            }
            else {
                //TODO ActionHistory
                if (isset($this->scenario)) {
                    switch ($this->scenario) {
                        case 'user_register':
                        case 'user_AsMember_register':
                            $this->USER_REGISTRATIONIP = IPHelper::IPtoBin(Yii::$app->request->userIP);
                            $this->USER_REGISTRATIONDATE = new Expression('NOW()');
                            break;
                        default:
                            break;
                    }
                }
            }
            return true;
        }
        return false;
    }

    public function afterFind() {
        if(parent::afterFind()){
            $this->USER_CREATIONIP = IPHelper::BinToStr($this->USER_CREATIONIP);
            $this->USER_REGISTRATIONIP = IPHelper::BinToStr($this->USER_REGISTRATIONIP);
            return true;
        }
    }

    // <editor-fold defaultstate="collapsed" desc="RELATIONS">

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getr_ActionHistories()
    {
        return $this->hasMany(ActionHistory::className(), ['USER_USER_ID' => 'USER_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getr_Belongs()
    {
        return $this->hasMany(Belong::className(), ['USER_USER_ID' => 'USER_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getr_Donations()
    {
        return $this->hasMany(Donation::className(), ['USER_ID' => 'USER_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getr_SocialAccounts()
    {
        return $this->hasMany(SOCIALACCOUNT::className(), ['USER_USER_ID' => 'USER_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getr_Tokens()
    {
        return $this->hasMany(Token::className(), ['USER_USER_ID' => 'USER_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getr_Country()
    {
        return $this->hasOne(Country::className(), ['CountryId' => 'COUNTRY_CountryId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getr_UserState()
    {
        return $this->hasOne(USERSTATE::className(), ['USERSTATE_ID' => 'USERSTATE_USERSTATE_ID']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function get_Works() {
        return $this->hasMany(WORK::className(), ['USER_USER_ID' => 'USER_ID']);
    }


    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="AUTH">
    public function getAuthKey() {
        return $this->USER_AUTHKEY;
    }

    public function getId() {
        return $this->USER_ID;
    }

    public function validateAuthKey($authKey) {
        return $this->getAuthKey() == $authKey;
    }

    /**
     * 
     * @param type $id
     * @return User
     */
    public static function findIdentity($id) {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        
    }   
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="GETTER">
    /**
     * Find a User by Email
     * @param type $userMail
     * @return type
     */
    public static function findByMail($userMail){
        \Yii::getLogger()->log('findByMail', Logger::LEVEL_TRACE);
        return User::findOne([
            'USER_MAIL' => strtolower($userMail)
        ]);
    }
    
    public function isConfirmed(){
        \Yii::getLogger()->log('isConfirmed', Logger::LEVEL_TRACE);
        return $this->USER_REGISTRATIONDATE != null;
    }
    
    
    /**
     * Get if User is blocked / banned or not
     * Todo
     * @return boolean
     */
    public function isBlocked(){
        \Yii::getLogger()->log('isBlocked', Logger::LEVEL_TRACE);
        return false;
    }

    // </editor-fold>

    /**
     * Create a new User.
     * @return boolean
     * @throws \RuntimeException
     * @throws Exception
     */
    public function create(){
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }
        
        $transaction = $this->getDb()->beginTransaction();
        
        try {
            $this->USER_SECRETKEY = PasswordHelper::generate(80);
            $this->USERSTATE_USERSTATE_ID = UserState::findOne(['USERSTATE_DEFAULT' => 1])->USERSTATE_ID;
            $this->USER_LDAPUID = \Yii::$app->security->generateRandomString(80);
            
            if ($this->save()) {
                \Yii::getLogger()->log('User has been created', Logger::LEVEL_INFO);
                
                //BELONG <> GROUP
                {
                    $belong = new Belong();
                    $belong->create($this->primaryKey);
                }
                //RBAC
                {
                    $userRole = \Yii::$app->authManager->getRole('user');
                    \Yii::$app->authManager->assign($userRole, $this->primaryKey);
                }
                //LDAP
                {
                    $ldap = new LDAPHelper();
                    $ldap->addUser($this->USER_NICKNAME, $this->USER_MAIL, $this->TMP_PASSWORD, $this->USER_LDAPUID);
                }
                
                ActionHistoryExt::ahUserCreation($this->primaryKey);
                
                $transaction->commit();
                return true;
            }
            else {
                \Yii::getLogger()->log('User hasn\'t been created'.VarDumper::dumpAsString($this->errors), Logger::LEVEL_WARNING);
                throw  new \ErrorException('User error at creation, see Model error.');
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
            \Yii::getLogger()->log('An error occurred while creating user account'.VarDumper::dumpAsString($ex), Logger::LEVEL_ERROR);
            throw $ex;
        }
        return false;
    }
    
    /**
     * Confirm a user Token
     * @param type $token
     * @return boolean
     */
    public function confirmToken($token){
        $token = Token::findOne(['TOKEN_CODE' => $token]);
            
        if($token != null 
                && !$token->getIsExpired() 
                && $token->USER_USER_ID == $this->USER_ID){
            
            switch ($token->TOKEN_TYPE) {
                case TokenExt::TYPE_USER_CONFIRMATION:
                    $this->setScenario('user_register');
                    break;
                case TokenExt::TYPE_MEMBER_CONFIRMATION:
                    $this->setScenario('user_AsMember_register');
                    break;
                case TokenExt::TYPE_CONFIRM_NEW_EMAIL:
                    break;
                case TokenExt::TYPE_RECOVERY:             
                    break;
                case TokenExt::TYPE_CNIL_ACCESS:
                    break;
                case TokenExt::TYPE_CNIL_PARTIAL_DELETE:
                    break;
                case TokenExt::TYPE_CNIL_FULL_DELETE:
                    return true;
                default:
                    Yii::getLogger()->log('Unknow Token type', Logger::LEVEL_ERROR);
            }          
            
            if($this->save()){
                $token->delete();
            }
            return true;
        }
        return false;        
    }
    
    /**
     * Update the password of an User
     * @param type $newPassword
     * @return boolean
     */
    public function updatePassword(){
        $transaction = $this->getDb()->beginTransaction();
        try {
            $this->USER_PASSWORD = PasswordHelper::hash($this->TMP_PASSWORD);  
            
            if ($this->update(FALSE) !== false) {
                \Yii::getLogger()->log('User password has been updated', Logger::LEVEL_INFO);
                \Yii::$app->session->setFlash('user.update_ok');
                
                $ldap = new LDAPHelper();
                $ldap->updateUser($this->USER_LDAPUID, [
                    'userPassword' => $this->TMP_PASSWORD,
                ]);
                
                $transaction->commit();
                return true;
            }
            else {
                \Yii::getLogger()->log('User password hasn\'t been updated'.VarDumper::dumpAsString($this->errors), Logger::LEVEL_WARNING);
                \Yii::$app->session->setFlash('user.update_ko');
            }
        } catch (Exception $exc) {
            $transaction->rollBack();
            \Yii::getLogger()->log('An error occurred while updating user password account'.VarDumper::dumpAsString($exc), Logger::LEVEL_ERROR);
        }
        return false;
    }
    
    /**
     * Delete all personnal data from User.
     * @return boolean
     */
    public function cnilPartialDelete(){
        $transaction = $this->getDb()->beginTransaction();
        try {
            //Retained Data
            $nickName = $this->USER_NICKNAME;
            $registrationDate = $this->USER_REGISTRATIONDATE;
            $creationDate = $this->USER_CREATIONDATE;
            $ldapId = $this->USER_LDAPUID;
            $secretKey = $this->USER_SECRETKEY;
            $fakeMail = "deleted@account.cnil";
            
            //Reset Data
            $this->loadDefaultValues(false);  
            
            //Set Data
            $this->USER_NICKNAME = $nickName ;
            $this->USER_REGISTRATIONDATE = $registrationDate;
            $this->USER_CREATIONDATE = $creationDate;
            $this->USER_LDAPUID = $ldapId;
            $this->USER_SECRETKEY = $secretKey;
            $this->USER_MAIL =  "deleted@account.cnil";
            $this->USER_ISDELETED = true;            
            
            if ($this->update(FALSE) !== false) {
                \Yii::getLogger()->log('User has been partially deleted.', Logger::LEVEL_INFO);
                
                //TODO
//                $ldap = new LDAPHelper();
                
                $transaction->commit();
                return true;
            }
            else {
                \Yii::getLogger()->log('User hasn\'t been partially deleted : '.VarDumper::dumpAsString($this->errors), Logger::LEVEL_WARNING);
            }
        } catch (Exception $exc) {
            $transaction->rollBack();
            \Yii::getLogger()->log('An error occurred while deleting partially a user account'.VarDumper::dumpAsString($exc), Logger::LEVEL_ERROR);
        }
        return false;
    }
    
    /**
     * Delete all data related from User except created Project
     * @return boolean
     */
    public function cnilFullDelete(){
        $transaction = $this->getDb()->beginTransaction();
        try {
            Token::deleteAll(['USER_USER_ID' => $this->USER_ID ]);
            ActionHistory::deleteAll(['USER_USER_ID' => $this->USER_ID ]);
            Belong::deleteAll(['USER_USER_ID' => $this->USER_ID ]);
            Work::deleteAll(['USER_USER_ID' => $this->USER_ID ]);
            $this->delete();
            //TODO
            // Unlink all donations
            // Delete SocialAccounts
            // $ldap = new LDAPHelper();
            
            $transaction->commit();
        } catch (Exception $exc) {
            $transaction->rollBack();
            \Yii::getLogger()->log('An error occurred while deleting partially a user account'.VarDumper::dumpAsString($exc), Logger::LEVEL_ERROR);
        }
        return false;
    }
    
    /**
     * Return array of LDAP Group Name
     * @return type
     */
    public function getLDAPGroup(){
        $belong = Belong::findOne([
            'USER_USER_ID' => $this->USER_ID,
            'BELONG_TO' => null
        ]);
        
        $toReturn = array();
        $toReturn[] = $belong->r_Group->GROUP_LDAPNAME;
        
        return $toReturn;
    }
    
    /**
     * Return array of LDAP Access Name
     * @return array
     */
    //TODO: add project access
    public function getLDAPAccess(){
        $belong = Belong::findOne([
            'USER_USER_ID' => $this->USER_ID,
            'BELONG_TO' => null
        ]);
        $services = $belong->r_Group->r_Services;
        
        $toReturn = array();
        
        foreach ($services as $service) {
            $toReturn[] = $service->SERVICE_LDAPNAME;
        }
        
        return $toReturn;
    }
}
