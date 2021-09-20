<?php

declare(strict_types=1);

namespace PickIt\Responses;

class RawResponse
{
    private string $response;
    private array $headers;

    public function __construct(string $response, array $headers)
    {
        $this->response = $response;
        $this->headers = $headers;
    }

    public function getRawResponse(): string
    {
        return $this->response;
    }

    public function getResponse(): array
    {
        return json_decode($this->response, true);
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
