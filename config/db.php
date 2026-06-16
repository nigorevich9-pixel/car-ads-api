<?php

return [
    "class" => yii\db\Connection::class,
    "dsn" => getenv("DB_DSN") ?: "pgsql:host=127.0.0.1;port=5432;dbname=car_ads",
    "username" => getenv("DB_USER") ?: "car_ads",
    "password" => getenv("DB_PASSWORD") ?: "car_ads",
    "charset" => "utf8",
];
