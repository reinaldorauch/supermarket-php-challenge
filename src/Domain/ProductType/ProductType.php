<?php

declare(strict_types=1);

namespace App\Domain\ProductType;

use JsonSerializable;

class ProductType implements JsonSerializable
{
    public string $name;
    public int $taxRate;
    public ?int $id;

    public static function createFromArray(array $data)
    {
        $prodType = new ProductType();

        $prodType->name = $data['name'];
        $prodType->taxRate = $data['taxRate'];
        $prodType->id = $data['id'] ?? null;

        return $prodType;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'taxRate' => $this->taxRate,
        ];
    }
}
