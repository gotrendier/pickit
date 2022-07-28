<?php

declare(strict_types=1);

namespace PickIt\Entities;

class MapPoint
{
    private int $id;
    private string $name;
    private float $lat;
    private float $lon;
    private string $address;
    private string $postalCode;
    private bool $isDropoffPoint;
    private bool $isPickupPoint;
    private string $weeklyOpeningHours;

    public function __construct(
        int $id,
        string $name,
        float $lat,
        float $lon,
        string $address,
        string $postalCode,
        bool $isDropoffPoint,
        bool $isPickupPoint,
        string $weeklyOpeningHours
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->lat = $lat;
        $this->lon = $lon;
        $this->address = $address;
        $this->postalCode = $postalCode;
        $this->isDropoffPoint = $isDropoffPoint;
        $this->isPickupPoint = $isPickupPoint;
        $this->weeklyOpeningHours = $weeklyOpeningHours;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function getLon(): float
    {
        return $this->lon;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function isDropoffPoint(): bool
    {
        return $this->isDropoffPoint;
    }

    public function isPickupPoint(): bool
    {
        return $this->isPickupPoint;
    }

    public function getWeeklyOpeningHours(): string
    {
        return $this->weeklyOpeningHours;
    }
}
