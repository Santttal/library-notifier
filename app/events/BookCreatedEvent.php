<?php

namespace app\events;

use app\models\Book;
use yii\base\Event;

class BookCreatedEvent extends Event
{
    public Book $book;
    /** @var int[] */
    public array $authorIds = [];
}
