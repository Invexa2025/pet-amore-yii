<?php

return [
    'db' => [
        'class' => 'yii\db\Connection',
        'attributes' => [
            PDO::ATTR_STRINGIFY_FETCHES => true,
            // PDO::ATTR_PERSISTENT => true
        ],
        'dsn' => $_ENV["DB_CONNECTION"] . ':host=' . $_ENV["DB_HOST"] . ';port=' . $_ENV["DB_PORT"] . ';dbname=' . $_ENV["DB_NAME"],
        'username' => $_ENV["DB_USERNAME"],
        'password' => $_ENV["DB_PASSWORD"],
        'charset' => 'utf8',
    ]
];
