<?php

namespace app\components\listeners;

use app\events\BookCreatedEvent;
use app\models\Book;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\Query;

class AuthorSubscriptionListener implements BootstrapInterface
{
    public function bootstrap($app)
    {
        Event::on(Book::class, Book::EVENT_CREATED, [$this, 'handle']);
    }

    public function handle(BookCreatedEvent $event): void
    {
        $authorIds = $event->authorIds ?? [];
        if (empty($authorIds)) {
            return;
        }

        $subscribers = (new Query())
            ->select([
                'u.id as user_id',
                'u.username',
                'u.phone',
                's.author_id',
            ])
            ->from(['s' => '{{%author_subscription}}'])
            ->innerJoin(['u' => '{{%user}}'], 'u.id = s.user_id')
            ->where(['s.author_id' => $authorIds])
            ->all();

        $notified = [];
        foreach ($subscribers as $subscriber) {
            if (empty($subscriber['phone'])) {
                continue;
            }
            if (isset($notified[$subscriber['user_id']])) {
                continue;
            }
            $message = sprintf(
                'New book "%s" released for one of your subscribed authors (%s).',
                $event->book->title,
                $subscriber['username']
            );

            Yii::info("SMS to {$subscriber['phone']}: {$message}", __METHOD__);
            // отправка SmsClient->send($phone, $message);
            $notified[$subscriber['user_id']] = true;
        }
    }
}
