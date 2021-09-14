<?php

namespace PickIt\Entities;

class Person
{
    private string $name;
    private string $lastName;
    private string $pid;
    private string $email;
    private string $phone;
    private Address $address;

    public function __construct (string $response, array $headers) {
        $this->response = $response;
        $this->headers = $headers;
    }
}