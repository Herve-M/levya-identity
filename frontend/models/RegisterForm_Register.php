<?php
/**
 * This file is part of Levya Identity.
 * 
 * Levya Identity is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Foobar. See file LICENSE(.md) in this source tree, 
 * if not, see <http://www.gnu.org/licenses/>.
 * 
 * Copyright (C) Levya Team Members
 */

namespace frontend\models;

use yii\base\Model;
use yii\log\Logger;
use common\models\User;
use common\models\Token;
use common\models\TokenExt;

use common\helpers\MailHelper;

use kartik\password\StrengthValidator;

class RegisterForm_Register extends Model
{
    /** @var string */
    public $USER_NICKNAME;

    /** @var string */
    public $USER_MAIL;

    /** @var string */
    public $USER_PASSWORD;

    /** @inheritdoc */
    public function rules()
    {
        return [
            //USER_NICKNAME
            [['USER_NICKNAME'], 'required'],
            [['USER_NICKNAME'], 'match', 'pattern' => '/^[\w]{3,15}$/'],
            [['USER_NICKNAME'], 'string', 'min' => 3, 'max' => 20],
            [['USER_NICKNAME'], 'unique', 'targetClass' => 'common\models\User',
                'message' => \Yii::t('app/user', 'This username has already been taken')],
            
            //USER_EMAIL
            [['USER_MAIL'], 'required'],
            [['USER_MAIL'], 'string', 'max' => 254],
            [['USER_MAIL'], 'email'],
            [['USER_MAIL'], 'unique', 'targetClass' => 'common\models\User',
                'message' => \Yii::t('app/user', 'This email address has already been taken')],
            
            [['USER_PASSWORD'], 'required'],
            [['USER_PASSWORD'], StrengthValidator::className(), 'preset'=>'fair', 'userAttribute'=>'USER_NICKNAME']
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'USER_MAIL' => \Yii::t('app/user', 'User  Mail'),
            'USER_NICKNAME' => \Yii::t('app/user', 'User  Nickname'),
            'USER_PASSWORD' => \Yii::t('app/user', 'User  Password'),
        ];
    }

    /** @inheritdoc */
    public function formName()
    {
        return 'registration-form';
    }

    /**
     * Creates new confirmation token and sends it to the user.
     *
     * @return bool
     */
    public function register()
    {
        \Yii::getLogger()->log('User Registration', Logger::LEVEL_TRACE);
        if ($this->validate()) {
            $user = new User([
                'scenario' => 'user_register',
                'USER_MAIL' => $this->USER_MAIL,
                'USER_NICKNAME' => $this->USER_NICKNAME,
                'TMP_PASSWORD' => $this->USER_PASSWORD,
                'USER_PASSWORD' => $this->USER_PASSWORD
            ]);
            
            if($user->create()){
                try {
                    $token = Token::createToken($user->USER_ID, TokenExt::TYPE_USER_CONFIRMATION);
                    MailHelper::registrationMail($user, $token);
                    \Yii::$app->session->setFlash('user.confirmation_sent');
                    return true;
                } catch (Exception $ex) {
                    \Yii::getLogger()->log('An error occurred while creating user account'.VarDumper::dumpAsString($ex), Logger::LEVEL_ERROR);
                }                
            }
        }
        return false;
    }
}
