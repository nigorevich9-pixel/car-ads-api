<?php

namespace app\services;

use app\entities\Car;
use app\entities\CarOption;
use app\models\requests\CreateCarRequest;
use app\repositories\CarRepositoryInterface;
use InvalidArgumentException;

final class CarService
{
    private const DEFAULT_PER_PAGE = 20;

    private CarRepositoryInterface $repository;

    public function __construct(CarRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createCar(CreateCarRequest $request): Car
    {
        if (!$request->validate()) {
            throw new InvalidArgumentException(json_encode($request->getErrors()));
        }

        $option = null;
        $optionData = $request->getOptionData();
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
            (string) $request->title,
            (string) $request->description,
            (float) $request->price,
            (string) $request->photo_url,
            (string) $request->contacts,
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
