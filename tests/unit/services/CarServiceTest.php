<?php

namespace tests\unit\services;

use app\entities\Car;
use app\models\requests\CreateCarRequest;
use app\repositories\CarRepositoryInterface;
use app\services\CarService;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CarServiceTest extends TestCase
{
    public function testCreateCarWithoutOptionsField(): void
    {
        $service = new CarService(new InMemoryCarRepository());
        $request = $this->makeRequest([
            "title" => "Toyota Camry",
            "description" => "Fresh car",
            "price" => 15000,
            "photo_url" => "https://example.com/camry.jpg",
            "contacts" => "+995555000000",
        ]);

        $car = $service->createCar($request);

        self::assertSame("Toyota Camry", $car->getTitle());
        self::assertNull($car->getOption());
    }

    public function testCreateCarWithNullOptions(): void
    {
        $service = new CarService(new InMemoryCarRepository());
        $request = $this->makeRequest([
            "title" => "Honda Civic",
            "description" => "Daily car",
            "price" => 9000,
            "photo_url" => "https://example.com/civic.jpg",
            "contacts" => "+995555111111",
            "options" => null,
        ]);

        $car = $service->createCar($request);

        self::assertSame("Honda Civic", $car->getTitle());
        self::assertNull($car->getOption());
    }

    public function testCreateCarWithFullOptions(): void
    {
        $service = new CarService(new InMemoryCarRepository());
        $request = $this->makeRequest([
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
        ]);

        $car = $service->createCar($request);

        self::assertNotNull($car->getOption());
        self::assertSame("BMW", $car->getOption()->getBrand());
        self::assertSame(2020, $car->getOption()->getYear());
    }

    public function testCreateCarRejectsIncompleteOptions(): void
    {
        $service = new CarService(new InMemoryCarRepository());
        $request = $this->makeRequest([
            "title" => "Audi A4",
            "description" => "Incomplete options",
            "price" => 18000,
            "photo_url" => "https://example.com/audi.jpg",
            "contacts" => "+995555333333",
            "options" => [
                "brand" => "Audi",
            ],
        ]);

        $this->expectException(InvalidArgumentException::class);

        $service->createCar($request);
    }

    private function makeRequest(array $data): CreateCarRequest
    {
        $request = new CreateCarRequest();
        $request->loadData($data);

        return $request;
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
