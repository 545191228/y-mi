<?php
namespace common\controllers;

use Yii;
use yii\web\Controller;

class BaseController extends Controller{
    function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function actionError(){
        exit('error');
    }
    
}