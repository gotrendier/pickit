<?php

namespace PickIt\Entities;

class MapPoint
{
    private int $id;
    private int $idService;
    private string $name;
    private string $serviceType;
    private float $lat;
    private float $lon;
    private string $address;
    private string $postalCode;
    private string $location;
    private string $province;

    public function __construct (int $id,
                                 int $idService,
                                 string $name,
                                 string $serviceType,
                                 string $lat,
                                 string $lon,
                                 string $address,
                                 string $postalCode,
                                 string $location,
                                 string $province
    ) {
        $this->id = $id;
        $this->idService = $idService;
        $this->name = $name;
        $this->serviceType = $serviceType;
        $this->lat = $lat;
        $this->lon = $lon;
        $this->address = $address;
        $this->postalCode = $postalCode;
        $this->location = $location;
        $this->province = $province;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getIdService(): int
    {
        return $this->idService;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getServiceType(): string
    {
        return $this->serviceType;
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

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getProvince(): string
    {
        return $this->province;
    }
}