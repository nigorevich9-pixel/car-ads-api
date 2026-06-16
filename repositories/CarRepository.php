<?php

namespace app\repositories;

use app\entities\Car;
use app\mappers\CarDataMapper;
use app\models\CarOptionRecord;
use app\models\CarRecord;
use RuntimeException;
use Yii;

final class CarRepository implements CarRepositoryInterface
{
    private CarDataMapper $mapper;

    public function __construct(CarDataMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function create(Car $car): Car
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $record = new CarRecord();
            $record->title = $car->getTitle();
            $record->description = $car->getDescription();
            $record->price = $car->getPrice();
            $record->photo_url = $car->getPhotoUrl();
            $record->contacts = $car->getContacts();

            if (!$record->save()) {
                throw new RuntimeException("Failed to save car: " . json_encode($record->getErrors()));
            }

            $option = $car->getOption();
            if ($option !== null) {
                $optionRecord = new CarOptionRecord();
                $optionRecord->car_id = $record->id;
                $optionRecord->brand = $option->getBrand();
                $optionRecord->model = $option->getModel();
                $optionRecord->year = $option->getYear();
                $optionRecord->body = $option->getBody();
                $optionRecord->mileage = $option->getMileage();

                if (!$optionRecord->save()) {
                    throw new RuntimeException("Failed to save car option: " . json_encode($optionRecord->getErrors()));
                }
            }

            $transaction->commit();
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        $created = $this->findById((int) $record->id);
        if ($created === null) {
            throw new RuntimeException("Created car was not found after save.");
        }

        return $created;
    }

    public function findById(int $id): ?Car
    {
        $record = CarRecord::find()
            ->with("option")
            ->where(["id" => $id])
            ->one();

        return $record ? $this->mapper->toEntity($record) : null;
    }

    public function findList(int $page, int $perPage): array
    {
        $page = max(1, $page);
        $perPage = max(1, $perPage);

        $records = CarRecord::find()
            ->with("option")
            ->orderBy(["id" => SORT_DESC])
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->all();

        return array_map(fn (CarRecord $record): Car => $this->mapper->toEntity($record), $records);
    }
}
