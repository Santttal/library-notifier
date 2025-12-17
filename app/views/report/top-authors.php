<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var array $years */
/** @var int|null $year */
/** @var array $authors */

$this->title = 'Top Authors by Year';
?>
<div class="report-top-authors">
    <h1 class="h3 mb-4"><?= Html::encode($this->title) ?></h1>

    <?php if (empty($years)): ?>
        <div class="alert alert-info">
            No books found. Add books to view this report.
        </div>
    <?php else: ?>
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['report/top-authors'],
            'options' => ['class' => 'row g-3 align-items-center mb-4'],
        ]); ?>
        <div class="col-auto">
            <?= Html::label('Year', 'year-select', ['class' => 'col-form-label']) ?>
        </div>
        <div class="col-auto">
            <?= Html::dropDownList('year', $year, array_combine($years, $years), [
                'class' => 'form-select',
                'id' => 'year-select',
            ]) ?>
        </div>
        <div class="col-auto">
            <?= Html::submitButton('Filter', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>

        <?php if (empty($authors)): ?>
            <div class="alert alert-warning">
                No authors found for <?= Html::encode($year) ?>.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Author</th>
                            <th>Books Released</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($authors as $index => $author): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= Html::encode($author['author']) ?></td>
                                <td><?= Html::encode($author['book_count']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
