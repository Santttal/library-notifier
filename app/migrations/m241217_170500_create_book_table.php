<?php

use yii\db\Migration;

class m241217_170500_create_book_table extends Migration
{
    private string $table = '{{%book}}';

    public function safeUp(): void
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'title' => $this->string(255)->notNull(),
            'release_year' => $this->smallInteger()->notNull(),
            'description' => $this->text(),
            'isbn' => $this->string(32)->notNull()->unique(),
            'cover_image' => $this->string(255),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex('idx_book_title', $this->table, 'title');
        $this->createIndex('idx_book_release_year', $this->table, 'release_year');
        $this->addForeignKey(
            'fk_book_author',
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
        $this->dropForeignKey('fk_book_author', $this->table);
        $this->dropTable($this->table);
    }
}
