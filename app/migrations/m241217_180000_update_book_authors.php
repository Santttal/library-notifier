<?php

use yii\db\Migration;

class m241217_180000_update_book_authors extends Migration
{
    private string $bookTable = '{{%book}}';
    private string $authorTable = '{{%author}}';
    private string $junctionTable = '{{%book_author}}';

    public function safeUp(): void
    {
        $this->createTable($this->junctionTable, [
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey('pk_book_author', $this->junctionTable, ['book_id', 'author_id']);
        $this->addForeignKey(
            'fk_book_author_book',
            $this->junctionTable,
            'book_id',
            $this->bookTable,
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_book_author_author',
            $this->junctionTable,
            'author_id',
            $this->authorTable,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $books = (new \yii\db\Query())
            ->select(['id', 'author_id'])
            ->from($this->bookTable)
            ->all();

        foreach ($books as $book) {
            if (!empty($book['author_id'])) {
                $this->insert($this->junctionTable, [
                    'book_id' => $book['id'],
                    'author_id' => $book['author_id'],
                ]);
            }
        }

        $this->dropForeignKey('fk_book_author', $this->bookTable);
        $this->dropColumn($this->bookTable, 'author_id');
    }

    public function safeDown(): void
    {
        $this->addColumn($this->bookTable, 'author_id', $this->integer()->notNull());
        $this->addForeignKey(
            'fk_book_author',
            $this->bookTable,
            'author_id',
            $this->authorTable,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $bookAuthors = (new \yii\db\Query())
            ->select(['book_id', 'author_id'])
            ->from($this->junctionTable)
            ->orderBy(['book_id' => SORT_ASC])
            ->all();

        $handled = [];
        foreach ($bookAuthors as $row) {
            if (isset($handled[$row['book_id']])) {
                continue;
            }
            $this->update($this->bookTable, ['author_id' => $row['author_id']], ['id' => $row['book_id']]);
            $handled[$row['book_id']] = true;
        }

        $this->dropForeignKey('fk_book_author_book', $this->junctionTable);
        $this->dropForeignKey('fk_book_author_author', $this->junctionTable);
        $this->dropTable($this->junctionTable);
    }
}
