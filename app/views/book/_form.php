<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Book $model */
/** @var array $authors */
?>

<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data'],
]); ?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'authorIds')->dropDownList($authors, [
    'prompt' => 'Select authors',
    'multiple' => true,
    'size' => 5,
]) ?>
<?= $form->field($model, 'release_year')->textInput(['type' => 'number', 'min' => 0]) ?>
<?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>
<?php if ($model->getCoverUrl()): ?>
    <div class="mb-3">
        <label class="form-label">Current Cover</label><br>
        <?= Html::img($model->getCoverUrl(), ['class' => 'img-thumbnail', 'style' => 'max-width: 200px']) ?>
    </div>
<?php endif; ?>
<?= $form->field($model, 'coverFile')->fileInput()->hint('Accepted: jpg, jpeg, png, webp. Max size 2 MB.') ?>
<?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>

<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Save', ['class' => 'btn btn-success']) ?>
    <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-secondary']) ?>
</div>

<?php ActiveForm::end(); ?>
