<?php

declare(strict_types=1);

namespace PickIt\Entities;

class Measure implements \JsonSerializable
{
    public const UNIT_KG = "kg";
    public const UNIT_G = "g";
    public const UNIT_CM = "cm";
    public const UNIT_M = "m";

    public const WEIGHT_UNITS = [
        self::UNIT_KG,
        self::UNIT_G,
    ];

    public const LENGTH_UNITS = [
        self::UNIT_CM,
        self::UNIT_M,
    ];

    private float $amount;
    private string $unit;

    public function __construct(float $amount, string $unit)
    {
        $this->amount = $amount;
        $this->unit = $unit;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function jsonSerialize(): array
    {
        return [
            "unit" => $this->getUnit(),
            "amount" => $this->getAmount(),
        ];
    }
}
