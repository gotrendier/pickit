<?php

declare(strict_types=1);

namespace PickIt\Requests;

use PickIt\Entities\Address;
use PickIt\Entities\Person;

class BudgetPetitionRequest implements \JsonSerializable
{
    private string $serviceType;
    private string $workflowTag;
    private int $operationType;
    private array $products = [];
    private string $tokenId = '';
    private int $slaId;
    private Person $customer;
    private ?int $pointId = null;
    private ?Address $retailerAlternativeAddress = null;

    public function __construct(
        string $serviceType,
        string $workflowTag,
        int $operationType,
        array $products,
        int $slaId,
        Person $customer
    ) {
        $this->serviceType = $serviceType;
        $this->workflowTag = $workflowTag;
        $this->operationType = $operationType;
        $this->products = $products;

        $this->slaId = $slaId;
        $this->customer = $customer;
    }

    public function setTokenId(string $tokenId): self
    {
        $this->tokenId = $tokenId;
        return $this;
    }

    public function setRetailerAlternativeAddress(Address $retailerAlternativeAddress): self
    {
        $this->retailerAlternativeAddress = $retailerAlternativeAddress;
        return $this;
    }

    public function setPointId(string $pointId): self
    {
        $this->pointId = (int)$pointId;
        return $this;
    }

    public function getPointId(): ?int
    {
        return $this->pointId;
    }

    public function getServiceType(): string
    {
        return $this->serviceType;
    }

    public function getWorkflowTag(): string
    {
        return $this->workflowTag;
    }

    public function getOperationType(): int
    {
        return $this->operationType;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function getRetailerAlternativeAddress(): ?Address
    {
        return $this->retailerAlternativeAddress;
    }

    public function getSlaId(): int
    {
        return $this->slaId;
    }

    public function getCustomer(): Person
    {
        return $this->customer;
    }

    public function getTokenId(): string
    {
        return $this->tokenId;
    }

    public function jsonSerialize(): array
    {
        $fields = [
            "serviceType" => $this->getServiceType(),
            "workflowTag" => $this->getWorkflowTag(),
            "operationType" => $this->getOperationType(),
            "retailer" => [
                "tokenId" => $this->getTokenId()
            ],
            "products" => $this->getProducts(),
            "sla" => [
                "id" => $this->getSlaId()
            ],
            "customer" => $this->getCustomer(),
            "pointId" => $this->getPointId(),
        ];

        if (!empty($this->getRetailerAlternativeAddress())) {
            $fields["retailerAlternativeAddress"] = $this->getRetailerAlternativeAddress();
        }

        return $fields;
    }
}
