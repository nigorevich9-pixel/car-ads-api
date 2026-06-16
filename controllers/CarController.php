<?php

namespace app\controllers;

use app\mappers\CarDataMapper;
use app\models\requests\CreateCarRequest;
use app\services\CarService;
use InvalidArgumentException;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

final class CarController extends Controller
{
    private CarService $service;
    private CarDataMapper $mapper;

    public function __construct($id, $module, CarService $service, CarDataMapper $mapper, $config = [])
    {
        $this->service = $service;
        $this->mapper = $mapper;
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            [
                "class" => ContentNegotiator::class,
                "formats" => [
                    "application/json" => Response::FORMAT_JSON,
                ],
            ],
            [
                "class" => VerbFilter::class,
                "actions" => [
                    "create" => ["POST"],
                    "view" => ["GET"],
                    "list" => ["GET"],
                ],
            ],
        ];
    }

    public function actionCreate(): array
    {
        $request = new CreateCarRequest();
        $request->loadData((array) \Yii::$app->request->getBodyParams());

        try {
            $car = $this->service->createCar($request);
        } catch (InvalidArgumentException $exception) {
            \Yii::$app->response->statusCode = 422;
            return [
                "errors" => json_decode($exception->getMessage(), true) ?: ["request" => [$exception->getMessage()]],
            ];
        }

        \Yii::$app->response->statusCode = 201;
        return $this->mapper->toResponse($car);
    }

    public function actionView(int $id): array
    {
        $car = $this->service->getCar($id);
        if ($car === null) {
            throw new NotFoundHttpException("Car advertisement not found.");
        }

        return $this->mapper->toResponse($car);
    }

    public function actionList(int $page = 1): array
    {
        $page = (int) \Yii::$app->request->get("page", $page);
        $cars = $this->service->listCars($page);

        return [
            "page" => max(1, $page),
            "items" => array_map(fn ($car): array => $this->mapper->toResponse($car), $cars),
        ];
    }
}
