<?php
namespace console\models;

use Yii;
use yii\db\ActiveRecord;

class Setting extends ActiveRecord{
    public static function tableName()
    {
        return parent::tableName('setting');
    }
}