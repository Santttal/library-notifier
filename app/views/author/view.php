<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Author $model */

$this->title = $model->full_name;
?>
<div class="author-view">
    <div class="d-flex justify-content-between mb-3">
        <h1 class="h3"><?= Html::encode($this->title) ?></h1>
        <div class="d-flex gap-2">
            <?php if (!Yii::$app->user->isGuest): ?>
                <?php if ($isSubscribed): ?>
                    <?= Html::beginForm(['unsubscribe', 'id' => $model->id], 'post', ['class' => 'd-inline']) ?>
                    <?= Html::submitButton('Unsubscribe', ['class' => 'btn btn-warning']) ?>
                    <?= Html::endForm() ?>
                <?php else: ?>
                    <?= Html::beginForm(['subscribe', 'id' => $model->id], 'post', ['class' => 'd-inline']) ?>
                    <?= Html::submitButton('Subscribe', ['class' => 'btn btn-success']) ?>
                    <?= Html::endForm() ?>
                <?php endif; ?>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this author?',
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
            'full_name',
            'created_at',
            'updated_at',
        ],
    ]) ?>
</div>
