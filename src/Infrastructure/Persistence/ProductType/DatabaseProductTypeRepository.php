<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\ProductType;

use PDO;
use App\Domain\ProductType\ProductType;
use App\Domain\ProductType\ProductTypeNotFoundException;
use App\Domain\ProductType\ProductTypeRepository;
use App\Infrastructure\Persistence\PostgresConnection;

class DatabaseProductTypeRepository implements ProductTypeRepository
{
    public function __construct(private PostgresConnection $db)
    {
    }

    /**
     * @return ProductType[]
     */
    public function findAll(): array
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->query('SELECT * FROM "product_type" WHERE "deletedAt" IS NULL', PDO::FETCH_CLASS, ProductType::class);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ProductType
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('SELECT "name", "taxRate", "id"  FROM "product_type" WHERE "deletedAt" IS NULL AND "id" = ?');
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
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('INSERT INTO "product_type" ("name", "taxRate", "createdAt", "updatedAt") VALUES (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP) RETURNING "id"');
        $stmt->bindParam(1, $prodType->name, PDO::PARAM_STR);
        $stmt->bindParam(2, $prodType->taxRate, PDO::PARAM_INT);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $prodType->setId($res['id']);

        return $prodType;
    }
}
