<?php

declare(strict_types=1);

namespace PickIt\Entities;

class Retailer
{
    private string $name;
    private string $responsable;
    private string $address;

    public function __construct(
        string $name,
        string $responsable,
        string $address
    ) {
        $this->name = $name;
        $this->responsable = $responsable;
        $this->address = $address;
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
}
