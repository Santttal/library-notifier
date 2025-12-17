<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Yii2 Sandbox';
?>
<div class="site-index">
    <div class="jumbotron text-center bg-transparent mt-4">
        <h1 class="display-5">Welcome to Yii2 Sandbox</h1>
        <p class="lead">Edit files under <code>app/</code> locally, run <code>composer install</code>, and refresh the browser.</p>
    </div>

    <div class="body-content">
        <div class="row mt-4">
            <div class="col-lg-4">
                <h3>Composer</h3>
                <p>Run <code>composer install</code> inside the <code>app</code> directory to download framework dependencies.</p>
            </div>
            <div class="col-lg-4">
                <h3>Docker</h3>
                <p>PHP-FPM mounts your working tree, and Nginx serves <code>web/</code>. Changes apply immediately.</p>
            </div>
            <div class="col-lg-4">
                <h3>Database</h3>
                <p>Use the bundled MySQL service. Connection settings come from environment variables in <code>config/db.php</code>.</p>
            </div>
        </div>
    </div>
</div>
