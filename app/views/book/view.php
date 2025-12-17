<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Book $model */

$this->title = $model->title;
?>
<div class="book-view">
    <div class="d-flex justify-content-between mb-3">
        <h1 class="h3"><?= Html::encode($this->title) ?></h1>
        <div>
            <?php if (!Yii::$app->user->isGuest): ?>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this book?',
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
            <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'label' => 'Authors',
                'value' => implode(', ', array_map(fn($a) => $a->full_name, $model->authors ?? [])) ?: '—',
            ],
            'release_year',
            'isbn',
            'description:ntext',
            [
                'label' => 'Cover Image',
                'format' => 'raw',
                'value' => $model->getCoverUrl()
                    ? Html::img($model->getCoverUrl(), ['style' => 'max-width:200px', 'class' => 'img-thumbnail'])
                    : '—',
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>
</div>
