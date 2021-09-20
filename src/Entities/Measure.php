<?php

declare(strict_types=1);

namespace PickIt\Entities;

class Measure
{

    public const TYPE_WEIGHT = 1;
    public const TYPE_LENGTH = 2;

    private float $amount;
    private string $unit;
    private int $type;

    public function __construct(float $amount, string $unit, int $type)
    {

        $this->amount = $amount;
        $this->unit = $unit;
        $this->type = $type;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function getType(): int
    {
        return $this->type;
    }
}
