<?php

/**
 * 后台管理员登录表单
 */
namespace admin\models\formes;

use Yii;
use yii\base\Model;
use admin\models\Admin;

class AdminLoginForm extends Model{
    public $user_name;
    public $user_pwd;
    public $login_time;
    public $login_ip;
    public $create_time;
    public $role_id;

    public function rules(){
        return [];
    }

}