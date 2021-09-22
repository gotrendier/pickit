<?php

declare(strict_types=1);

namespace PickIt\Entities;

class Address implements \JsonSerializable
{
    private string $postalCode;
    private string $address;
    private string $city;
    private string $province;

    public function __construct(
        string $postalCode,
        string $address,
        string $city,
        string $province
    ) {
        $this->postalCode = $postalCode;
        $this->address = $address;
        $this->city = $city;
        $this->province = $province;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getProvince(): string
    {
        return $this->province;
    }

    public function jsonSerialize(): array
    {
        return [
            "postalCode" => $this->getPostalCode(),
            "address" => $this->getAddress(),
            "city" => $this->getCity(),
            "province" => $this->getProvince()
        ];
    }
}
