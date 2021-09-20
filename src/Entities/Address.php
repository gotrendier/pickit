<?php

declare(strict_types=1);

namespace PickIt\Entities;

class Address
{
    private string $postCode;
    private string $address;
    private string $city;
    private string $province;

    public function __construct(array $response)
    {
        $this->postCode = $response["postalCode"];
        $this->address = $response["address"];
        $this->city = $response["city"];
        $this->province = $response["province"];
    }

    public function getPostCode(): string
    {
        return $this->postCode;
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
}
