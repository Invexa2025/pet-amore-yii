<?php

namespace app\controllers;
use app\extensions\XController;

use Yii;

class AdminDashboardController extends XController
{
    public function actionIndex()
    {
        return $this->render('admin-dashboard');
    }

    public function actionLogout()
    {
        $this->destroyAllSession();
		$this->redirectUrl('login');
    }
}
