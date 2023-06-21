<?php

declare(strict_types=1);

namespace App\Domain\User;

use JsonSerializable;

class User implements JsonSerializable
{
    public ?int $id;

    public string $username;

    public string $firstName;

    public string $lastName;

    public string $passwordHash;

    static function from(?int $id, string $username, string $firstName, string $lastName)
    {
        $u = new User();
        $u->id = $id;
        $u->username = $username;
        $u->firstName = $firstName;
        $u->lastName = $lastName;
        return $u;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    function setPassword(string $password): void
    {
        $this->passwordHash = password_hash($password, PASSWORD_ARGON2ID);
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
        ];
    }
}
