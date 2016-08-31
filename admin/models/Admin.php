<?php
namespace admin\models;

use Yii;
use yii\db\ActiveRecord;

class Admin extends ActiveRecord
{
    public static function tableName()
    {
        return parent::tableName('ymi_admin');
    }
}