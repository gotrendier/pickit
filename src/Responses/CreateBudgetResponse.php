<?php

declare(strict_types=1);

namespace PickIt\Responses;

class CreateBudgetResponse extends RawResponse
{
    private float $price;
    private float $totalPrice;
    private float $tax;
    private ?string $mapImageUrl = null;
    private ?string $urlMap = null;
    private array $products;
    private string $uuid;
    private ?array $point = null;
    private ?array $hours = null;

    public function __construct(RawResponse $rawResponse)
    {
        parent::__construct($rawResponse->getRawResponse(), $rawResponse->getHeaders());

        $response = $rawResponse->getResponse();

        $this->price = $response["price"];
        $this->totalPrice = $response["totalPrice"];
        $this->tax = $response["tax"];
        $this->products = $response["products"];
        $this->uuid = $response["uuid"];

        $optionalFields = [
            "mapImageUrl",
            "urlMap",
            "hours",
            "point",
        ];

        foreach ($optionalFields as $field) {
            if (!empty($response[$field])) {
                $this->{$field} = $response[$field];
            }
        }
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function getTax(): float
    {
        return $this->tax;
    }

    public function getMapImageUrl(): string
    {
        return $this->mapImageUrl;
    }

    public function getUrlMap(): string
    {
        return $this->urlMap;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getPoint(): array
    {
        return $this->point;
    }

    public function getHours(): array
    {
        return $this->hours;
    }
}
