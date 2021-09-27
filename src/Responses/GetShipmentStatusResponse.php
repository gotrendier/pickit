<?php

declare(strict_types=1);

namespace PickIt\Responses;

class GetShipmentStatusResponse extends RawResponse
{
    public function __construct(RawResponse $rawResponse)
    {
        parent::__construct($rawResponse->getRawResponse(), $rawResponse->getHeaders());

        $response = $rawResponse->getResponse();
    }
}
