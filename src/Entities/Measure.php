<?php

namespace PickIt\Entities;

class Measure {

    public const TYPE_WEIGHT = 1;
    public const TYPE_LENGTH = 2;

    private float $amount;
    private string $unit;
    private int $type;

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