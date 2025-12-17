<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    public function rules(): array
    {
        return [
            [['username', 'password_hash', 'auth_key', 'phone'], 'required'],
            [['username'], 'string', 'max' => 64],
            [['password_hash', 'role'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['access_token'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 12],
            [['username'], 'unique'],
            [['phone'], 'unique'],
            ['phone', 'match', 'pattern' => '/^\+7\d{10}$/', 'message' => 'Phone must match +7XXXXXXXXXX'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'phone' => 'Phone',
            'role' => 'Role',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public static function findByUsername(string $username): ?self
    {
        return static::findOne(['username' => $username]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->auth_key === $authKey;
    }

    public function setPassword(string $password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function validatePassword($password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function getRole(): string
    {
        return $this->role ?? 'user';
    }

    public function getAuthorSubscriptions()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('{{%author_subscription}}', ['user_id' => 'id']);
    }

    public function isSubscribedToAuthor(int $authorId): bool
    {
        return AuthorSubscription::find()
            ->where(['user_id' => $this->id, 'author_id' => $authorId])
            ->exists();
    }

    public function subscribeToAuthor(int $authorId): void
    {
        if ($this->isSubscribedToAuthor($authorId)) {
            return;
        }
        $subscription = new AuthorSubscription([
            'user_id' => $this->id,
            'author_id' => $authorId,
        ]);
        $subscription->save(false);
    }

    public function unsubscribeFromAuthor(int $authorId): void
    {
        AuthorSubscription::deleteAll(['user_id' => $this->id, 'author_id' => $authorId]);
    }
}
