<?php

namespace app\entities;

final class CarOption
{
    private ?int $id;
    private ?int $carId;
    private string $brand;
    private string $model;
    private int $year;
    private string $body;
    private int $mileage;

    public function __construct(
        ?int $id,
        ?int $carId,
        string $brand,
        string $model,
        int $year,
        string $body,
        int $mileage
    ) {
        $this->id = $id;
        $this->carId = $carId;
        $this->brand = $brand;
        $this->model = $model;
        $this->year = $year;
        $this->body = $body;
        $this->mileage = $mileage;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarId(): ?int
    {
        return $this->carId;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getMileage(): int
    {
        return $this->mileage;
    }
}
