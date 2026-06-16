<?php

namespace tests\unit\models\requests;

use app\models\requests\CreateCarRequest;
use PHPUnit\Framework\TestCase;

final class CreateCarRequestTest extends TestCase
{
    public function testRejectsIncompleteOptions(): void
    {
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

        self::assertFalse($request->validate());
        self::assertArrayHasKey("options", $request->getErrors());
    }

    public function testRejectsMissingTitle(): void
    {
        $request = $this->makeRequest([
            "description" => "Missing title",
            "price" => 18000,
            "photo_url" => "https://example.com/audi.jpg",
            "contacts" => "+995555333333",
            "options" => null,
        ]);

        self::assertFalse($request->validate());
        self::assertArrayHasKey("title", $request->getErrors());
    }

    public function testReturnsValidatedData(): void
    {
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

        self::assertTrue($request->validate());

        $data = $request->getValidatedData();
        self::assertSame("BMW 320", $data["title"]);
        self::assertSame("BMW", $data["options"]["brand"]);
    }

    private function makeRequest(array $data): CreateCarRequest
    {
        $request = new CreateCarRequest();
        $request->loadData($data);

        return $request;
    }
}
