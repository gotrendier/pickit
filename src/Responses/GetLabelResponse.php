<?php

declare(strict_types=1);

namespace PickIt\Responses;

use PickIt\Entities\MapPoint;
use PickIt\Entities\Person;
use PickIt\Entities\Retailer;
use DateTime;

class GetLabelResponse extends RawResponse
{
    private Person $customer;
    private string $pickItCode;
    private string $barcode;
    private MapPoint $pickItPoint;
    private Retailer $retailer;
    private DateTime $date;
    private string $shipmentId;
    private string $operationType;
    private string $canalization;
    private int $serviceId;
    private string $order;
    private string $barcodeUrl;
    private array $labelsUrls;

    public function __construct(RawResponse $rawResponse)
    {
        parent::__construct($rawResponse->getRawResponse(), $rawResponse->getHeaders());

        $response = $rawResponse->getResponse();

        $this->customer = new Person($response["customer"]["name"], $response["customer"]["lastName"]);
        $this->barcode = $response["barcode"];
        $this->pickItPoint = new MapPoint(
            empty($response["pickitPoint"]["id"]) ? 0 : (int)$response["pickitPoint"]["id"],
            "",
            0,
            0,
            $response["pickitPoint"]["address"],
            "",
            true,
            true,
            ""
        );

        $this->retailer = new Retailer(
            $response["retailer"]["name"],
            $response["retailer"]["responsable"],
            $response["retailer"]["address"],
            $response["retailer"]["postalCode"],
            $response["retailer"]["city"],
            $response["retailer"]["province"],
        );

        $this->date = new DateTime($response["date"]);
        $this->pickItCode = $response["pickitCode"];
        $this->shipmentId = $response["shipmentID"];
        $this->operationType = $response["operationType"];
        $this->canalization = $response["canalization"];
        $this->serviceId = $response["serviceId"];
        $this->order = $response["trackinginfo"]["order"];
        $this->barcodeUrl = $response["url"]["barCode"];
        $this->labelsUrls = $response["url"]["label"];
    }

    public function getCustomer(): Person
    {
        return $this->customer;
    }

    public function getPickItCode(): string
    {
        return $this->pickItCode;
    }

    public function getBarcode()
    {
        return $this->barcode;
    }

    public function getPickItPoint(): MapPoint
    {
        return $this->pickItPoint;
    }

    public function getRetailer(): Retailer
    {
        return $this->retailer;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getShipmentId(): string
    {
        return $this->shipmentId;
    }

    public function getOperationType(): string
    {
        return $this->operationType;
    }

    public function getCanalization(): string
    {
        return $this->canalization;
    }

    public function getServiceId(): int
    {
        return $this->serviceId;
    }

    public function getOrder(): string
    {
        return $this->order;
    }

    public function getBarcodeUrl(): string
    {
        return $this->barcodeUrl;
    }

    public function getLabelsUrls(): array
    {
        return $this->labelsUrls;
    }
}
