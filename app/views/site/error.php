<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="alert alert-danger mt-3">
        <?= nl2br(Html::encode($message)) ?>
    </div>
    <p class="mt-3">
        Please contact support if you believe this is a server error.
    </p>
</div>
