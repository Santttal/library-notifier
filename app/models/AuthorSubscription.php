<?php

namespace app\models;

use yii\db\ActiveRecord;

class AuthorSubscription extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%author_subscription}}';
    }

    public static function primaryKey()
    {
        return ['user_id', 'author_id'];
    }
}
