<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Html;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?php
NavBar::begin([
    'brandLabel' => 'Yii2 Sandbox',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => ['class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top'],
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav ms-auto'],
    'items' => [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'Authors', 'url' => ['/author/index']],
        ['label' => 'Books', 'url' => ['/book/index']],
        ['label' => 'Top Authors', 'url' => ['/report/top-authors']],
        Yii::$app->user->isGuest
            ? ['label' => 'Login', 'url' => ['/site/login']]
            : '<li class="nav-item">'
                . Html::beginForm(['/site/logout'], 'post', ['class' => 'd-inline'])
                . Html::submitButton(
                    'Logout (' . Html::encode(Yii::$app->user->identity->username) . ')',
                    ['class' => 'btn btn-link nav-link logout']
                )
                . Html::endForm()
                . '</li>',
    ],
]);
NavBar::end();
?>

<main class="container py-5 mt-5">
    <?= $content ?>
</main>

<footer class="footer text-center text-muted py-3">
    &copy; <?= date('Y') ?> Yii2 Sandbox
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
