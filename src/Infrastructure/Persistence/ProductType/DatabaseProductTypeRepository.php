<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\ProductType;

use PDO;
use App\Domain\ProductType\ProductType;
use App\Domain\ProductType\ProductTypeNotFoundException;
use App\Domain\ProductType\ProductTypeRepository;

class DatabaseProductTypeRepository implements ProductTypeRepository
{
    public function __construct(private PDO $db)
    {
    }

    /**
     * @return ProductType[]
     */
    public function findAll(): array
    {
        return $this->db->query(
            'SELECT "name", "taxRate", "id" FROM "product_type" WHERE "deletedAt" IS NULL',
            PDO::FETCH_CLASS,
            ProductType::class
        )->fetchAll();
    }

    public function findById(int $id): ProductType
    {
        $stmt = $this->db->prepare(
            'SELECT "name", "taxRate", "id"  
            FROM "product_type" WHERE "deletedAt" IS NULL AND "id" = ?'
        );
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, ProductType::class);
        $stmt->execute();
        if (!$prod = $stmt->fetch()) {
            throw new ProductTypeNotFoundException();
        }
        return $prod;
    }

    public function create(array $data): ProductType
    {
        $prodType = ProductType::createFromArray($data);
        $stmt = $this->db->prepare(
            'INSERT INTO "product_type" ("name", "taxRate", "createdAt", "updatedAt") 
            VALUES (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP) RETURNING "id"'
        );
        $stmt->bindParam(1, $prodType->name, PDO::PARAM_STR);
        $stmt->bindParam(2, $prodType->taxRate, PDO::PARAM_INT);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $prodType->setId($res['id']);
        return $prodType;
    }
}
