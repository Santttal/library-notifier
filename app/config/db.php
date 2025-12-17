<?php

return [
    'class' => \yii\db\Connection::class,
    'dsn' => getenv('DB_DSN') ?: 'mysql:host=db;port=3306;dbname=yii2',
    'username' => getenv('DB_USER') ?: 'yii2',
    'password' => getenv('DB_PASSWORD') ?: 'secret',
    'charset' => 'utf8mb4',
    'enableSchemaCache' => !YII_DEBUG,
    'attributes' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ],
];
