<?php

declare(strict_types=1);

namespace PickIt\Responses;

class WebhookResponse extends RawResponse
{
    private string $token;
    private string $pickItCode;
    private array $state;
    private string $order;
    private array $points;

    public function __construct(
        RawResponse $rawResponse
    ) {
        parent::__construct($rawResponse->getRawResponse(), []);

        $response = $rawResponse->getResponse();

        $this->token = $response["token"];
        $this->pickItCode = $response["pickitCode"];
        $this->state = $response["state"];
        $this->order = $response["order"];
        $this->points = $response["points"];
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

    public function isStateInPreparation(): bool
    {
        if (!isset($this->state['tag'])) {
            return false;
        }

        return $this->state['tag'] === 'inDropOffPoint'
            && $this->state['subState']['tag'] === 'availableForDropDropoff';
    }

    public function isStateReadyToBeDispatched(): bool
    {
        if (!isset($this->state['tag'])) {
            return false;
        }

        return $this->state['tag'] === 'courier'
            && is_null($this->state['subState']);
    }

    public function isStateOnRoute(): bool
    {
        if (!isset($this->state['tag'])) {
            return false;
        }

        return $this->state['tag'] === 'courier';
    }

    public function isReadyToBePicked(): bool
    {
        if (!isset($this->state['tag'])) {
            return false;
        }

        return in_array($this->state['tag'], ['inPikcitPoint', 'inPickitPoint']);
    }

    public function isStateDelivered(): bool
    {
        if (!isset($this->state['tag'])) {
            return false;
        }

        return $this->state['tag'] === 'delivered';
    }

    public function isStatePointAvailableForCollect(): bool
    {
        if (!isset($this->state['tag'])) {
            return false;
        }

        if (!isset($this->state['subState']['tag'])) {
            return false;
        }

        return $this->state['tag'] === 'point'
            && $this->state['subState']['tag'] === 'availableForCollect';
    }

    public function isStateExpired(): bool
    {
        if (!isset($this->state['tag'])) {
            return false;
        }

        return $this->state['tag'] === 'expired';
    }

    public function isStateEnded(): bool
    {
        if (!isset($this->state['tag'])) {
            return false;
        }

        return $this->state['tag'] === 'ended';
    }

    public function isStateAvailableForCollect(): bool
    {
        if (!isset($this->state['tag'])) {
            return false;
        }

        return $this->state['tag'] === 'availableForCollectDOPoint';
    }

    public function isStateReturnToSender(): bool
    {
        if (!isset($this->state['tag'])) {
            return false;
        }

        return $this->state['tag'] === 'returnToSender';
    }

    public function isStateRefundRetailer(): bool
    {
        if (!isset($this->state['tag'])) {
            return false;
        }

        if (!isset($this->state['subState']['tag'])) {
            return false;
        }

        return $this->state['tag'] === 'courier'
            && $this->state['subState']['tag'] === 'refundRetailer';
    }

    public function isStateAvailableForCollectOrigin(): bool
    {
        if (!isset($this->state['tag'])) {
            return false;
        }

        if (!isset($this->state['subState']['tag'])) {
            return false;
        }

        return $this->state['tag'] === 'inDropOffPoint'
            && $this->state['subState']['tag'] === 'availableForCollectDOPoint';
    }

    public function isStateReturnedToSender(): bool
    {
        if (!isset($this->state['tag'])) {
            return false;
        }

        return $this->state['tag'] === 'returnedToSender';
    }
}
