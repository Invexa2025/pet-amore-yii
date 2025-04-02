<?php

namespace app\controllers;
use app\extensions\XController;

use Yii;

class DashboardController extends XController
{
    public function actionIndex()
    {
        return $this->render('dashboard');
    }

    public function actionLogout()
    {
        $this->destroyAllSession();
		$this->redirectUrl('login');
    }
}
