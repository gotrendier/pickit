<?php

namespace PickIt\Responses;

use PickIt\Entities\MapPoint;
use PickIt\Entities\Paginator;

class GetMapPointResponse extends RawResponse
{
    /**
     * @var MapPoint[]
     */
    private array $points = [];
    private ?Paginator $paginator = null;

    public function __construct(RawResponse $rawResponse)
    {
        parent::__construct($rawResponse->getRawResponse(), $rawResponse->getHeaders());

        $response = $rawResponse->getResponse();

        foreach ($response["result"] as $point) {
            $this->points[] = new MapPoint(
                $point["id"],
                $point["idService"],
                $point["name"],
                $point["serviceType"],
                $point["lat"],
                $point["lng"],
                $point["address"],
                $point["postalCode"],
                $point["location"],
                $point["province"],
            );
        }

        if (!empty($response["paginator"])) {
            $this->paginator = new Paginator(
                $response["paginator"]["total"],
                $response["paginator"]["perPage"],
                $response["paginator"]["page"],
                $response["paginator"]["totalPages"],
            );
        }
    }

    public function getPaginator () : ?Paginator {
        return $this->paginator;
    }

    public function getPoints(): array
    {
        return $this->points;
    }
}