<?php

namespace app\controllers;
use yii\web\Controller;
use Yii;

class SpeedTestController extends Controller
{
    public function actionIndex()
    {
        $this->layout = 'speed-test-layout.php';
        
        return $this->render('speed-test');
    }
}
