<?php

declare(strict_types=1);

namespace PickIt\Entities;

use InvalidArgumentException;

class Product implements \JsonSerializable
{
    private string $name;
    private Measure $weight;
    private Measure $length;
    private Measure $height;
    private Measure $width;
    private float $price;
    private ?string $sku = null;
    private int $amount = 1;

    public function __construct(
        string $name,
        Measure $weight,
        Measure $length,
        Measure $width,
        Measure $height,
        float $price
    ) {
        if (!in_array($weight->getUnit(), Measure::WEIGHT_UNITS)) {
            throw new InvalidArgumentException("invalid weight unit received (" . $weight->getUnit() . ") for " . $name);
        }
        if (!in_array($length->getUnit(), Measure::LENGTH_UNITS)) {
            throw new InvalidArgumentException("invalid length unit received (" . $length->getUnit() . ") for " . $name);
        }
        if (!in_array($height->getUnit(), Measure::LENGTH_UNITS)) {
            throw new InvalidArgumentException("invalid length unit received (" . $height->getUnit() . ") for " . $name);
        }
        if (!in_array($width->getUnit(), Measure::LENGTH_UNITS)) {
            throw new InvalidArgumentException("invalid length unit received (" . $width->getUnit() . ") for " . $name);
        }

        $this->name = $name;
        $this->weight = $weight;
        $this->length = $length;
        $this->width = $width;
        $this->height = $height;
        $this->price = $price;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;
        return $this;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWeight(): Measure
    {
        return $this->weight;
    }

    public function getLength(): Measure
    {
        return $this->length;
    }

    public function getHeight(): Measure
    {
        return $this->height;
    }

    public function getWidth(): Measure
    {
        return $this->width;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function jsonSerialize(): array
    {
        $fields = [
            "name" => $this->getName(),
            "weight" => $this->getWeight(),
            "length" => $this->getLength(),
            "height" => $this->getHeight(),
            "width" => $this->getWidth(),
            "amount" => $this->getAmount(),
            "price" => $this->getPrice()
        ];

        if (!empty($this->getSku())) {
            $fields["sku"] = $this->getSku();
        }

        return $fields;
    }
}
