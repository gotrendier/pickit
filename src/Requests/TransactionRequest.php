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
    private ?string $shipmentTrackingCode = null;
    private string $orderTrackingCode;

    public function __construct(
        int $firstState,
        string $orderTrackingCode
    ) {
        $this->firstState = $firstState;
        $this->orderTrackingCode = $orderTrackingCode;
    }

    public function getOrderTrackingCode(): string
    {
        return $this->orderTrackingCode;
    }

    public function getShipmentTrackingCode(): ?string
    {
        return $this->shipmentTrackingCode;
    }

    public function setShipmentTrackingCode(?string $shipmentTrackingCode): void
    {
        $this->shipmentTrackingCode = $shipmentTrackingCode;
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
            "trakingInfo" => [ // expected typo...
                "order" => $this->getOrderTrackingCode()
            ],
        ];

        if (!empty($this->getShipmentTrackingCode())) {
            $fields["trakingInfo"]["shipment"] = $this->getShipmentTrackingCode();
        }

        if (!empty($this->getStartTime())) {
            $fields["deliveryTimeRange"] = [
                "start" => $this->getStartTime()->format("Y-m-d\TH:i:s\Z"),
                "end" => $this->getEndTime()->format("Y-m-d\TH:i:s\Z"),
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
