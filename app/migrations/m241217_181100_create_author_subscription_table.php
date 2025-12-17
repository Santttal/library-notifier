<?php

use yii\db\Migration;

class m241217_181100_create_author_subscription_table extends Migration
{
    private string $table = '{{%author_subscription}}';

    public function safeUp(): void
    {
        $this->createTable($this->table, [
            'user_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->addPrimaryKey('pk_author_subscription', $this->table, ['user_id', 'author_id']);
        $this->addForeignKey(
            'fk_author_subscription_user',
            $this->table,
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_author_subscription_author',
            $this->table,
            'author_id',
            '{{%author}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown(): void
    {
        $this->dropForeignKey('fk_author_subscription_user', $this->table);
        $this->dropForeignKey('fk_author_subscription_author', $this->table);
        $this->dropTable($this->table);
    }
}
