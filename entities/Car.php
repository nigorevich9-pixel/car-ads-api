<?php

namespace app\entities;

use DateTimeImmutable;

final class Car
{
    private ?int $id;
    private string $title;
    private string $description;
    private float $price;
    private string $photoUrl;
    private string $contacts;
    private ?DateTimeImmutable $createdAt;
    private ?CarOption $option;

    public function __construct(
        ?int $id,
        string $title,
        string $description,
        float $price,
        string $photoUrl,
        string $contacts,
        ?DateTimeImmutable $createdAt,
        ?CarOption $option
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->photoUrl = $photoUrl;
        $this->contacts = $contacts;
        $this->createdAt = $createdAt;
        $this->option = $option;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getPhotoUrl(): string
    {
        return $this->photoUrl;
    }

    public function getContacts(): string
    {
        return $this->contacts;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getOption(): ?CarOption
    {
        return $this->option;
    }
}
