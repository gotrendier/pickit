<?php

declare(strict_types=1);

namespace PickIt\Requests;

use PickIt\Entities\Address;
use PickIt\Entities\Person;

class SimplifiedTransactionRequest
{
    private ?string $observations = null;
    private ?int $pointId = null;
    private int $packageAmount = 1; // default value as listed in https://dev.pickit.net/Metodos.html#Met_POST/apiV2/transaction
    private ?\DateTime $startTime = null;
    private ?\DateTime $endTime = null;
    private string $serviceType;
    private string $workflowTag;
    private string $operationType;
    private array $products = [];
    private Address $retailerAlternativeAddress;
    private int $slaId;
    private Person $customer;
    private string $firstState;

    public function __construct(
        string $serviceType,
        string $workflowTag,
        string $operationType,
        array $products,
        Address $retailerAlternativeAddress,
        int $slaId,
        Person $customer,
        string $firstState
    ) {
        $this->serviceType = $serviceType;
        $this->workflowTag = $workflowTag;
        $this->operationType = $operationType;
        $this->products = $products;
        $this->retailerAlternativeAddress = $retailerAlternativeAddress;
        $this->slaId = $slaId;
        $this->customer = $customer;
        $this->firstState = $firstState;
    }

    public function setDeliveryTimeRange(\DateTime $startTime, \DateTime $endTime): self
    {
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        return $this;
    }

    public function setObservations(string $observations): self
    {
        $this->observations = $observations;
        return $this;
    }

    public function setPointId(int $pointId): self
    {
        $this->pointId = $pointId;
        return $this;
    }

    public function setPackageAmount(int $amount): self
    {
        $this->packageAmount = $amount;
        return $this;
    }

    public function getObservations(): ?string
    {
        return $this->observations;
    }

    public function getPointId(): ?int
    {
        return $this->pointId;
    }

    public function getPackageAmount(): int
    {
        return $this->packageAmount;
    }

    public function getStartTime(): ?\DateTime
    {
        return $this->startTime;
    }

    public function getEndTime(): ?\DateTime
    {
        return $this->endTime;
    }

    public function getServiceType(): string
    {
        return $this->serviceType;
    }

    public function getWorkflowTag(): string
    {
        return $this->workflowTag;
    }

    public function getOperationType(): string
    {
        return $this->operationType;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function getRetailerAlternativeAddress(): Address
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

    public function getFirstState(): string
    {
        return $this->firstState;
    }
}
