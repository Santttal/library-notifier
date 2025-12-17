<?php

use yii\db\Migration;

class m241217_181000_create_user_table extends Migration
{
    private string $table = '{{%user}}';

    public function safeUp(): void
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'username' => $this->string(64)->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'access_token' => $this->string(64),
            'phone' => $this->string(12)->notNull()->unique(),
            'role' => $this->string(16)->notNull()->defaultValue('user'),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        $security = \Yii::$app->security;
        $this->insert($this->table, [
            'username' => 'admin',
            'password_hash' => $security->generatePasswordHash('admin'),
            'auth_key' => $security->generateRandomString(),
            'access_token' => $security->generateRandomString(64),
            'phone' => '+79990000000',
            'role' => 'admin',
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable($this->table);
    }
}
