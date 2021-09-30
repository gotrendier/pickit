<?php

declare(strict_types=1);

namespace PickIt\Responses;

class StartTransactionResponse extends RawResponse
{
    private int $transactionId;
    private string $pickItCode;
    private string $urlTracking;
    private float $price;
    private ?array $deliveryTimeRange;

    public function __construct(RawResponse $rawResponse)
    {
        parent::__construct($rawResponse->getRawResponse(), $rawResponse->getHeaders());

        $response = $rawResponse->getResponse();

        $this->transactionId = $response["transactionId"];
        $this->pickItCode = $response["pickitCode"];
        $this->urlTracking = $response["urlTracking"];
        $this->price = $response["price"];
        $this->deliveryTimeRange = $response["deliveryTimeRange"];
    }

    public function getTransactionId(): int
    {
        return $this->transactionId;
    }

    public function getPickItCode(): string
    {
        return $this->pickItCode;
    }

    public function getUrlTracking(): string
    {
        return $this->urlTracking;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getDeliveryTimeRange(): array
    {
        return $this->deliveryTimeRange;
    }
}
