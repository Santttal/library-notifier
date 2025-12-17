<?php

namespace app\controllers;

use app\models\Book;
use yii\db\Query;
use yii\web\Controller;

class ReportController extends Controller
{
    public function actionTopAuthors(?int $year = null): string
    {
        $years = Book::find()
            ->select('release_year')
            ->distinct()
            ->orderBy(['release_year' => SORT_DESC])
            ->column();

        if (!$year && !empty($years)) {
            $year = (int) $years[0];
        }

        $authors = [];
        if ($year) {
            $authors = (new Query())
                ->select([
                    'author' => 'a.full_name',
                    'book_count' => 'COUNT(DISTINCT b.id)',
                ])
                ->from(['b' => '{{%book}}'])
                ->innerJoin(['ba' => '{{%book_author}}'], 'ba.book_id = b.id')
                ->innerJoin(['a' => '{{%author}}'], 'a.id = ba.author_id')
                ->where(['b.release_year' => $year])
                ->groupBy(['a.id', 'a.full_name'])
                ->orderBy(['book_count' => SORT_DESC, 'a.full_name' => SORT_ASC])
                ->limit(10)
                ->all();
        }

        return $this->render('top-authors', [
            'years' => $years,
            'year' => $year,
            'authors' => $authors,
        ]);
    }
}
