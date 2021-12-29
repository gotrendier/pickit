<?php

declare(strict_types=1);

namespace PickIt\Exceptions;

use PickIt\Responses\RawResponse;

class UnexpectedPickItResponseException extends \Exception
{

    private ?RawResponse $response = null;

    public function __construct(?RawResponse $response = null)
    {
        parent::__construct("Request failed " . (!empty($response) ? $response->getRawResponse() : ""));
        $this->response = $response;
    }

    public function getRawResponse(): ?RawResponse
    {
        return $this->response;
    }
}
