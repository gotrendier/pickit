<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PickIt\PickItClient;
use PickIt\Requests\TransactionRequest;

final class TransactionTest extends TestCase
{
    private PickItClient $pickItClient;

    public function setup(): void
    {
        $this->pickItClient = new PickItClient("", "", "mx", true);
    }

    public function testThrowAnExceptionWhenUuidIsEmpty(): void
    {
        $request = new TransactionRequest(PickItClient::START_TYPE_RETAILER, "333");

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("uuid is empty");

        $this->pickItClient->createTransaction("", $request);
    }

    public function testThrowAnExceptionWhenStartTypeIsInvalid(): void
    {
        $request = new TransactionRequest(99999, "333");

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid firstState");

        $this->pickItClient->createTransaction("56", $request);
    }

    public function testThrowAnExceptionWhenFirstStateIsCourierAndTrackingIsEmpty(): void
    {
        $request = new TransactionRequest(PickItClient::START_TYPE_COURIER, "333");

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("shipmentTrackingCode is empty");

        $this->pickItClient->createTransaction("56", $request);
    }

    /**
     * @dataProvider getDevolutionStartTypes
     */
    public function testThrowAnExceptionWhenFirstStateIsDevolutionAndTimeRangeIsEmpty(int $startType): void
    {
        $request = new TransactionRequest($startType, "333");

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("deliveryTimeRangeStart is empty");

        $this->pickItClient->createTransaction("56", $request);
    }

    public function getDevolutionStartTypes(): array
    {
        return [
            [
                PickItClient::START_TYPE_PROGRAMMED_DEVOLUTION
            ],
            [
                PickItClient::START_TYPE_REQUESTED_DEVOLUTION
            ]
        ];
    }
}
