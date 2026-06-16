<?php

use app\repositories\CarRepository;
use app\repositories\CarRepositoryInterface;

$config = [
    "id" => "car-ads-api",
    "basePath" => dirname(__DIR__),
    "bootstrap" => ["log"],
    "components" => [
        "request" => [
            "cookieValidationKey" => getenv("COOKIE_VALIDATION_KEY") ?: "change-me-for-local-development",
            "enableCsrfValidation" => false,
            "parsers" => [
                "application/json" => yii\web\JsonParser::class,
            ],
        ],
        "response" => [
            "format" => yii\web\Response::FORMAT_JSON,
        ],
        "db" => require __DIR__ . "/db.php",
        "urlManager" => [
            "enablePrettyUrl" => true,
            "showScriptName" => false,
            "rules" => [
                "POST car/create" => "car/create",
                "GET car/list" => "car/list",
                "GET car/<id:\d+>" => "car/view",
            ],
        ],
        "log" => [
            "traceLevel" => YII_DEBUG ? 3 : 0,
            "targets" => [
                [
                    "class" => yii\log\FileTarget::class,
                    "levels" => ["error", "warning"],
                ],
            ],
        ],
    ],
    "container" => [
        "definitions" => [
            CarRepositoryInterface::class => CarRepository::class,
        ],
    ],
];

return $config;
