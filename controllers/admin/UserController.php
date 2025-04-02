<?php

namespace app\controllers\admin;

use Yii;
use app\extensions\XController;
use app\models\admin\User;

class UserController extends XController
{
    public function actionChangePassword()
	{
		$user = new User();
        $user->db = $this->setDb();
		$user->Id = $this->getSession('USER_UID');
		$user->currPassword = $this->getParam('currPassword');
		$user->newPassword = $this->getParam('newPassword');
        $user->scenario = 'change-password';
        
		if ($user->validate())
		{
			$this->beginTx();
			$data = $user->changePassword();
			$this->validateBasicTx($data);

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($user->errors);
		}
	}
}