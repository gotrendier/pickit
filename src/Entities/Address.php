<?php

declare(strict_types=1);

namespace PickIt\Entities;

class Address
{
    private string $postalCode;
    private string $address;
    private string $city;
    private string $province;

    public function __construct(
        string $postalCode,
        string $address,
        string $city
    ) {
        $this->postalCode = $postalCode;
        $this->address = $address;
        $this->city = $city;
    }

    public function setProvince(string $province)
    {
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
        $fields = [
            "postalCode" => $this->getPostalCode(),
            "address" => $this->getAddress(),
            "city" => $this->getCity(),
        ];

        if (!empty($this->getProvince())) {
            $fields["province"] = $this->getProvince();
        }

        return $fields;
    }
}
