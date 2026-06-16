<?php

use app\repositories\CarRepository;
use app\repositories\CarRepositoryInterface;

return [
    "id" => "car-ads-api-console",
    "basePath" => dirname(__DIR__),
    "controllerNamespace" => "app\\commands",
    "components" => [
        "db" => require __DIR__ . "/db.php",
    ],
    "container" => [
        "definitions" => [
            CarRepositoryInterface::class => CarRepository::class,
        ],
    ],
];
