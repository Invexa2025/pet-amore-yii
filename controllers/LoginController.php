<?php

namespace app\controllers;
use app\extensions\XController;
use app\models\Login;
use Yii;

class LoginController extends XController
{
    public function actionIndex()
    {
        $this->destroyAllSession();
        
        $this->layout = 'login-layout.php';
        
        return $this->render('login');
    }

    public function actionLogin()
    {
        $login = new Login();
        $login->userId = $this->getParam('userId');
        $login->password = $this->getParam('password');
		$login->scenario = 'login';

		if ($login->validate())
		{
			$login->setDb($this->db);
			$data = $login->tryLogin();

			$this->setSessionId();
			
			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($login->errors);
		}
    }

    public function setSessionId()
    {
        // Change session ID (so that session file for each user is identifiable in the server)
        $sessionId = $this->getSession('USER_UID') . '-' . uniqid() . "-";
        $sessionIdLength = 50;
        $randomNumberLength = $sessionIdLength - strlen($sessionId);

        for ($i = 0 ; $i < $randomNumberLength; $i++)
        {
            $sessionId .= mt_rand(0,9);
        }

        $sessionId = preg_replace('/[^a-zA-Z0-9-,]/', ",", $sessionId);

        session_id($sessionId);
    }

    public function refreshSession($data)
    {
        $this->setSession([
			'USER_UID' 				=> $data['data']['user_uid'],
            'USER_ID'               => $data['data']['user_id'],
            'OFFICE_ID' 			=> $data['data']['office_id'],
			'OFFICE_CODE' 			=> $data['data']['office_code'],
			'OFFICE_NAME' 			=> $data['data']['office_name'],
			'OFFICE_TIMEZONE' 		=> $data['data']['OFFICE_TIMEZONE'],
			'OFFICE_UTC' 			=> $data['data']['OFFICE_UTC'],
			'FIRST_NAME' 			=> $data['data']['first_name'],
			'LAST_NAME' 			=> $data['data']['last_name'],
            'ROLE_TYPE' 			=> $data['data']['role_type'],
            'BUSINESS_ID' 			=> $data['data']['business_id'],
			'BUSINESS_NAME' 		=> $data['data']['business_name'],
			'EMAIL' 				=> $data['data']['email'],
            'SERVER_TIMEZONE'   	=> $data['data']['SERVER_TIMEZONE'],
			'SERVER_UTC' 			=> $data['data']['SERVER_UTC'],
			'IS_LOGIN' 				=> 1,
            'APPS' 				    => $data['privilege'],
			'ALL_APPS'      	    => $data['allPrivilege'],
		]);
    }

    public function actionReloadSession()
    {
        if (isset(Yii::$app->session['USER_UID']))
        {
            $login = new Login();
            $login->setDb($this->db);
            $data = $login->reloadSession();

            $this->destroyAllSession();
            $this->refreshSession($data);
        }

		return $this->jsonEncode([
			'errNum' 	=> 0,
            'errStr'    => Yii::t('app', 'Success')
		]);
    }
}
