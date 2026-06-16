<?php

namespace app\services;

use app\entities\Car;
use app\entities\CarOption;
use app\repositories\CarRepositoryInterface;

final class CarService
{
    private const DEFAULT_PER_PAGE = 20;

    private CarRepositoryInterface $repository;

    public function __construct(CarRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createCar(array $data): Car
    {
        $option = null;
        $optionData = $data["options"];
        if ($optionData !== null) {
            $option = new CarOption(
                null,
                null,
                (string) $optionData["brand"],
                (string) $optionData["model"],
                (int) $optionData["year"],
                (string) $optionData["body"],
                (int) $optionData["mileage"]
            );
        }

        $car = new Car(
            null,
            (string) $data["title"],
            (string) $data["description"],
            (float) $data["price"],
            (string) $data["photo_url"],
            (string) $data["contacts"],
            null,
            $option
        );

        return $this->repository->create($car);
    }

    public function getCar(int $id): ?Car
    {
        return $this->repository->findById($id);
    }

    /**
     * @return Car[]
     */
    public function listCars(int $page, int $perPage = self::DEFAULT_PER_PAGE): array
    {
        return $this->repository->findList($page, $perPage);
    }
}
