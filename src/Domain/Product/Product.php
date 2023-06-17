<?php

declare(strict_types=1);

namespace App\Domain\Product;

use App\Domain\ProductType\ProductType;
use JsonSerializable;
use PDO;

class Product implements JsonSerializable
{
    public string $name;
    public int $price;
    public ProductType|int $type;
    private ?int $id = null;

    static function createFromArray(array $data)
    {
        $prod = new Product();

        $prod->name = $data['name'];
        $prod->price = $data['price'];
        $prod->type = $data['type'];
        $prod->id = $data['id'] ?? null;

        return $prod;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFieldsMap(): array
    {
        return [
            'name' => ['value' => $this->name, 'type' => PDO::PARAM_STR],
            'price' => ['value' => $this->price, 'type' => PDO::PARAM_INT],
            'type' => ['value' => $this->type instanceof ProductType ? $this->type->getId() : $this->type, 'type' => PDO::PARAM_INT],
        ];
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'type' =>  $this->type instanceof ProductType ? $this->type->jsonSerialize() : $this->type,
        ];
    }
}
