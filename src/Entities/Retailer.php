<?php

declare(strict_types=1);

namespace PickIt\Entities;

class Retailer
{
    private string $name;
    private string $responsable;
    private string $address;
    private string $postCode;
    private string $city;
    private string $province;

    public function __construct(
        string $name,
        string $responsable,
        string $address,
        string $postCode,
        string $city,
        string $province
    ) {
        $this->name = $name;
        $this->responsable = $responsable;
        $this->address = $address;
        $this->postCode = $postCode;
        $this->city = $city;
        $this->province = $province;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getResponsable(): string
    {
        return $this->responsable;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getPostCode(): string
    {
        return $this->postCode;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getProvince(): string
    {
        return $this->province;
    }
}
