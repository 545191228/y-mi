<?php
namespace common\controllers;

use Yii;
use yii\web\Controller;

class SiteController extends Controller{
    function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function actionIndex(){
        exit('aaa');
    }

    public function actionError(){
        exit('error_common');
    }
}