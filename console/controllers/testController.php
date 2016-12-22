<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use console\models\Setting;

class testController extends Controller{
    public function actionIndex(){
        $setting = Setting::findAll();
        echo '<pre>';print_r($setting);exit;
    }
}