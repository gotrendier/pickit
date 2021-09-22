<?php

declare(strict_types=1);

namespace PickIt\Entities;

class Person implements \JsonSerializable
{
    private string $name;
    private string $lastName;
    private string $pid;
    private string $email;
    private ?string $phone = null;
    private ?Address $address = null;

    public function __construct(string $name, string $lastName)
    {
        $this->name = $name;
        $this->lastName = $lastName;
    }

    public function setNationalId(string $pid): self
    {
        $this->pid = $pid;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getPid(): string
    {
        return $this->pid;
    }

    public function setPid(string $pid): self
    {
        $this->pid = $pid;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function jsonSerialize(): array
    {
        $fields = [
            "name" => $this->getName(),
            "lastName" => $this->getLastName(),
        ];

        if (!empty($this->getPid())) {
            $fields["pid"] = (int)$this->getPid(); // documentation talks about type string, but it explodes if it isn't an int
        }
        if (!empty($this->getEmail())) {
            $fields["email"] = $this->getEmail();
        }

        if (!empty($this->getPhone())) {
            $fields["phone"] = $this->getPhone();
        }

        if (!empty($this->getAddress())) {
            $fields["address"] = $this->getAddress();
        }

        return $fields;
    }
}
