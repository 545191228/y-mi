<?php
namespace admin\controllers;

use Yii;
use yii\web\Controller;

class BaseController extends Controller{
    function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->checkLogin();
    }
    private function checkLogin(){
        //echo 'login';
    }
}