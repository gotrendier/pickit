<?php

namespace PickIt\Responses;

class GetLabelResponse
{
    private array $response;

    public function __construct (RawResponse $response) {
        $this->response = $response->getResponse();
    }
}