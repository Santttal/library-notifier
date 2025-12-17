<?php

use yii\db\Migration;

class m241217_170000_create_author_table extends Migration
{
    private string $table = '{{%author}}';

    public function safeUp(): void
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'full_name' => $this->string(255)->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex('idx_author_full_name', $this->table, 'full_name');
    }

    public function safeDown(): void
    {
        $this->dropTable($this->table);
    }
}
