<?php

namespace app\models;

use app\events\BookCreatedEvent;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class Book extends ActiveRecord
{
    public const EVENT_CREATED = 'book.created';
    /** @var UploadedFile|null */
    public $coverFile = null;
    /** @var array<int> */
    public $authorIds = [];

    public static function tableName(): string
    {
        return '{{%book}}';
    }

    public function rules(): array
    {
        return [
            [['title', 'release_year', 'isbn'], 'required'],
            [['release_year'], 'integer'],
            [['description'], 'string'],
            [['title', 'cover_image'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 32],
            [['isbn'], 'unique'],
            [['authorIds'], 'required'],
            [['authorIds'], 'each', 'rule' => ['integer']],
            ['authorIds', 'validateAuthorIds'],
            [
                ['coverFile'],
                'file',
                'extensions' => ['png', 'jpg', 'jpeg', 'webp'],
                'maxSize' => 2 * 1024 * 1024,
                'tooBig' => 'Cover image must be at most 2 MB.',
                'skipOnEmpty' => true,
            ],
        ];
    }

    public function beforeValidate(): bool
    {
        if (is_string($this->authorIds)) {
            $this->authorIds = [$this->authorIds];
        }
        if ($this->authorIds === null) {
            $this->authorIds = [];
        }
        if (is_array($this->authorIds)) {
            $this->authorIds = array_values(array_map('intval', array_filter($this->authorIds, fn($value) => $value !== null && $value !== '')));
        }
        return parent::beforeValidate();
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'authorIds' => 'Authors',
            'title' => 'Title',
            'release_year' => 'Release Year',
            'description' => 'Description',
            'isbn' => 'ISBN',
            'cover_image' => 'Cover Image',
            'coverFile' => 'Cover Image',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function validateAuthorIds($attribute): void
    {
        $ids = array_unique(array_filter((array) $this->$attribute));
        if (empty($ids)) {
            $this->addError($attribute, 'Select at least one author.');
            return;
        }

        $count = Author::find()->where(['id' => $ids])->count();
        if ($count !== count($ids)) {
            $this->addError($attribute, 'One or more authors are invalid.');
        }

        $this->$attribute = array_values($ids);
    }

    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('{{%book_author}}', ['book_id' => 'id']);
    }

    public function getCoverUrl(): ?string
    {
        if (!$this->cover_image) {
            return null;
        }
        $path = ltrim($this->cover_image, '/');
        return Yii::getAlias('@web/' . $path);
    }

    public function saveCoverFile(): ?string
    {
        if (!$this->coverFile) {
            return $this->cover_image;
        }

        $relativeDir = 'uploads';
        $filename = uniqid('cover_', true) . '.' . $this->coverFile->extension;
        $relativePath = $relativeDir . '/' . $filename;
        $absolutePath = Yii::getAlias('@webroot/' . $relativePath);

        FileHelper::createDirectory(dirname($absolutePath));

        if ($this->coverFile->saveAs($absolutePath)) {
            $this->deleteCoverFile();
            return $relativePath;
        }

        return $this->cover_image;
    }

    public function deleteCoverFile(): void
    {
        if (!$this->cover_image) {
            return;
        }

        $absolute = Yii::getAlias('@webroot/' . ltrim($this->cover_image, '/'));
        if (is_file($absolute)) {
            @unlink($absolute);
        }
    }

    public function afterFind(): void
    {
        parent::afterFind();
        $this->authorIds = $this->getAuthors()->select('id')->column();
    }

    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);
        $this->syncAuthors();
        if ($insert) {
            $this->authorIds = $this->getAuthors()->select('id')->column();
            $event = new BookCreatedEvent([
                'book' => $this,
                'authorIds' => $this->authorIds,
            ]);
            $this->trigger(self::EVENT_CREATED, $event);
        }
    }

    public function afterDelete(): void
    {
        $this->deleteCoverFile();
        parent::afterDelete();
        $this->unlinkAll('authors', true);
    }

    private function syncAuthors(): void
    {
        $current = $this->getAuthors()->select('id')->column();
        $new = $this->authorIds ?? [];
        sort($current);
        sort($new);
        if ($current === $new) {
            return;
        }
        $this->unlinkAll('authors', true);
        foreach ($new as $authorId) {
            if ($author = Author::findOne($authorId)) {
                $this->link('authors', $author);
            }
        }
    }
}
