<?php
namespace common\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;

    private $_user = false;

    public function rules()
    {
        return [
            [['username'], 'required'],
        ];
    }

    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser()/*, $this->rememberMe ? 3600 * 24 * 30 : 0*/);
        } else {
            return false;
        }
    }

    public function getUser()
    {
        if ($this->_user  === false) {
            $this->_user = User::findByUsername($this->username);
			
			// if user not found we just create it
			if(!$this->user) {
				$this->_user = User::newUser($this->username);
			}
		}

        return $this->_user;
    }
}
