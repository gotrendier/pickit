<?php

declare(strict_types=1);

namespace PickIt\Responses;

use PickIt\Entities\TrackingStatus;

class GetShipmentStatusResponse extends RawResponse
{
    public const STATUS_DELIVERED = "ENTREGADO";
    public const STATUS_ON_ROUTE = "EN CAMINO";
    public const STATUS_READY_TO_BE_DISPATCHED = "LISTO PARA RETIRO";
    public const STATUS_IN_PREPARATION = "EN PREPARACIÓN";

    private string $trackingNumber;
    private string $pickItNumber;
    private string $status;
    private string $title;
    private bool $subscription = false;

    /**
     * @var TrackingStatus[]
     */
    private array $movements = [];

    public function __construct(RawResponse $rawResponse)
    {
        parent::__construct($rawResponse->getRawResponse(), $rawResponse->getHeaders());

        $response = $rawResponse->getResponse();

        $this->trackingNumber = $response["trackingNumber"];
        $this->pickItNumber = $response["pickitNumber"];
        $this->status = $response["status"];
        $this->title = $response["title"];
        $this->subscription = !empty($response["subscription"]);

        foreach ($response["tracking"] as $status) {
            // because following a standard was too much...
            $date = explode("/", $status["date"]);
            $time = explode(" ", $date[2]);
            $date[2] = $time[0];
            $time = $time[1];

            $this->movements[] = new TrackingStatus(
                $status["title"],
                $status["description"],
                new \DateTime($date[2] . "-" . $date[1] . "-" . $date[0] . " " . $time),
                !empty($status["pointId"]) ? (int)$status["pointId"] : null,
                $status["marker"],
                $status["color"],
            );
        }
    }

    public function getTrackingNumber(): string
    {
        return $this->trackingNumber;
    }

    public function getPickItNumber(): string
    {
        return $this->pickItNumber;
    }

    public function getMovements(): array
    {
        return $this->movements;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function isSubscription(): bool
    {
        return $this->subscription;
    }

    public function getLatestStatus(): string
    {
        $lastMovement = $this->getMovements();
        $lastMovement = $lastMovement[count($lastMovement) - 1];

        return $lastMovement->getTitle();
    }
}
