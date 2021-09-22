<?php

declare(strict_types=1);

namespace PickIt\Requests;

class TransactionRequest implements \JsonSerializable
{
    private ?string $observations = null;

    private int $packageAmount = 1; // default value as listed in https://dev.pickit.net/Metodos.html#Met_POST/apiV2/transaction
    private ?\DateTime $startTime = null;
    private ?\DateTime $endTime = null;

    private int $firstState;
    private ?string $refundProbableCause = null;

    public function __construct(
        int $firstState
    ) {
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

    public function setPackageAmount(int $amount): self
    {
        $this->packageAmount = $amount;
        return $this;
    }

    public function getObservations(): ?string
    {
        return $this->observations;
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

    public function getFirstState(): int
    {
        return $this->firstState;
    }

    public function getRefundProbableCause(): ?string
    {
        return $this->refundProbableCause;
    }

    public function setRefundProbableCause(string $refundProbableCause): self
    {
        $this->refundProbableCause = $refundProbableCause;
        return $this;
    }

    public function jsonSerialize(): array
    {
        $fields = [
            "firstState" => $this->getFirstState(),
            "packageAmount" => $this->getPackageAmount(),
        ];

        if (!empty($this->getStartTime())) {
            $fields["deliveryTimeRange"] = [
                "start" => $this->getStartTime(),
                "end" => $this->getEndTime(),
            ];
        }
        if (!empty($this->getRefundProbableCause())) {
            $fields["refundProbableCause"] = $this->getRefundProbableCause();
        }
        if (!empty($this->getObservations())) {
            $fields["observations"] = $this->getObservations();
        }

        return $fields;
    }
}
