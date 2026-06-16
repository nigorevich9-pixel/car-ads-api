<?php

namespace tests\unit\services;

use app\entities\Car;
use app\repositories\CarRepositoryInterface;
use app\services\CarService;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class CarServiceTest extends TestCase
{
    public function testCreateCarWithoutOptionsField(): void
    {
        $service = new CarService(new InMemoryCarRepository());
        $data = [
            "title" => "Toyota Camry",
            "description" => "Fresh car",
            "price" => 15000,
            "photo_url" => "https://example.com/camry.jpg",
            "contacts" => "+995555000000",
            "options" => null,
        ];

        $car = $service->createCar($data);

        self::assertSame("Toyota Camry", $car->getTitle());
        self::assertNull($car->getOption());
    }

    public function testCreateCarWithNullOptions(): void
    {
        $service = new CarService(new InMemoryCarRepository());
        $data = [
            "title" => "Honda Civic",
            "description" => "Daily car",
            "price" => 9000,
            "photo_url" => "https://example.com/civic.jpg",
            "contacts" => "+995555111111",
            "options" => null,
        ];

        $car = $service->createCar($data);

        self::assertSame("Honda Civic", $car->getTitle());
        self::assertNull($car->getOption());
    }

    public function testCreateCarWithFullOptions(): void
    {
        $service = new CarService(new InMemoryCarRepository());
        $data = [
            "title" => "BMW 320",
            "description" => "Good condition",
            "price" => 22000,
            "photo_url" => "https://example.com/bmw.jpg",
            "contacts" => "+995555222222",
            "options" => [
                "brand" => "BMW",
                "model" => "320",
                "year" => 2020,
                "body" => "sedan",
                "mileage" => 45000,
            ],
        ];

        $car = $service->createCar($data);

        self::assertNotNull($car->getOption());
        self::assertSame("BMW", $car->getOption()->getBrand());
        self::assertSame(2020, $car->getOption()->getYear());
    }

}

final class InMemoryCarRepository implements CarRepositoryInterface
{
    private int $nextId = 1;

    /** @var array<int, Car> */
    private array $cars = [];

    public function create(Car $car): Car
    {
        $saved = new Car(
            $this->nextId++,
            $car->getTitle(),
            $car->getDescription(),
            $car->getPrice(),
            $car->getPhotoUrl(),
            $car->getContacts(),
            new DateTimeImmutable("2026-01-01 00:00:00"),
            $car->getOption()
        );

        $this->cars[$saved->getId()] = $saved;

        return $saved;
    }

    public function findById(int $id): ?Car
    {
        return $this->cars[$id] ?? null;
    }

    public function findList(int $page, int $perPage): array
    {
        return array_values($this->cars);
    }
}
