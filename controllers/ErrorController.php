<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class ErrorController extends Controller
{
	public function actionError()
    {
        $this->layout = 'error';
        
        if (Yii::$app->request->isAjax)
        {
            $this->layout = 'ajax-error-layout';
        }

        $exception = Yii::$app->errorHandler->exception;

        return $this->render('error', [
            'exception' => $exception
        ]);
    }
}