<?php

declare(strict_types=1);

namespace PickIt\Responses;

class WebhookResponse
{
    private string $token;
    private string $pickItCode;
    private array $state;
    private string $order;
    private array $points;

    public function __construct(
        string $token,
        string $pickItCode,
        array $state,
        string $order,
        array $points
    ) {
        $this->token = $token;
        $this->pickItCode = $pickItCode;
        $this->state = $state;
        $this->order = $order;
        $this->points = $points;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getPickItCode(): string
    {
        return $this->pickItCode;
    }

    public function getState(): array
    {
        return $this->state;
    }

    public function getOrder(): string
    {
        return $this->order;
    }

    public function getPoints(): array
    {
        return $this->points;
    }
}
