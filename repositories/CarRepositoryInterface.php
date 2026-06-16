<?php

namespace app\repositories;

use app\entities\Car;

interface CarRepositoryInterface
{
    public function create(Car $car): Car;

    public function findById(int $id): ?Car;

    /**
     * @return Car[]
     */
    public function findList(int $page, int $perPage): array;
}
