<?php
namespace admin\models;

use Yii;
use yii\db\ActiveRecord;

class WechatUser extends ActiveRecord{
    public static function tableName()
    {
        return parent::tableName('wechat_user');
    }
}