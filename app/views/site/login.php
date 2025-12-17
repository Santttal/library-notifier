<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\LoginForm $model */

$this->title = 'Login';
?>
<div class="site-login row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <h1 class="h3 mb-4 text-center"><?= Html::encode($this->title) ?></h1>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'layout' => 'default',
        ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'rememberMe')->checkbox() ?>

        <div class="form-group d-grid">
            <?= Html::submitButton('Login', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <p class="text-muted small mt-3">
            Credentials: <code>admin / admin</code>
        </p>
    </div>
</div>
