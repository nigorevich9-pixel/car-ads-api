<?php

namespace app\mappers;

use app\entities\Car;
use app\entities\CarOption;
use app\models\CarOptionRecord;
use app\models\CarRecord;
use DateTimeImmutable;

final class CarDataMapper
{
    public function toEntity(CarRecord $record): Car
    {
        return new Car(
            (int) $record->id,
            (string) $record->title,
            (string) $record->description,
            (float) $record->price,
            (string) $record->photo_url,
            (string) $record->contacts,
            $record->created_at ? new DateTimeImmutable((string) $record->created_at) : null,
            $record->option ? $this->optionToEntity($record->option) : null
        );
    }

    public function optionToEntity(CarOptionRecord $record): CarOption
    {
        return new CarOption(
            (int) $record->id,
            (int) $record->car_id,
            (string) $record->brand,
            (string) $record->model,
            (int) $record->year,
            (string) $record->body,
            (int) $record->mileage
        );
    }

    public function toResponse(Car $car): array
    {
        $option = $car->getOption();

        return [
            "id" => $car->getId(),
            "title" => $car->getTitle(),
            "description" => $car->getDescription(),
            "price" => $car->getPrice(),
            "photo_url" => $car->getPhotoUrl(),
            "contacts" => $car->getContacts(),
            "created_at" => $car->getCreatedAt()?->format("Y-m-d H:i:s"),
            "options" => $option ? [
                "brand" => $option->getBrand(),
                "model" => $option->getModel(),
                "year" => $option->getYear(),
                "body" => $option->getBody(),
                "mileage" => $option->getMileage(),
            ] : null,
        ];
    }
}
