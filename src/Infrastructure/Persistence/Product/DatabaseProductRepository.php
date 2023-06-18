<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Product;

use App\Domain\Product\Product;
use App\Domain\Product\ProductNotFoundException;
use App\Domain\Product\ProductRepository;
use App\Infrastructure\Persistence\PostgresConnection;
use PDO;

class DatabaseProductRepository implements ProductRepository
{
    public function __construct(private PostgresConnection $db)
    {
    }

    public function findAll()
    {
        return $this->db->getConnection()
            ->query('SELECT * FROM "product" WHERE "deletedAt" IS NULL', PDO::FETCH_CLASS, Product::class)
            ->fetchAll();
    }

    public function findById(int $id): Product
    {
        $stmt = $this->db->getConnection()
            ->prepare('SELECT "name", "price", "productTypeId" as "type", "id" FROM "product" WHERE "deletedAt" IS NULL AND "id" = :id');
        $stmt->setFetchMode(PDO::FETCH_CLASS, Product::class);
        $stmt->bindParam('id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if (!$res = $stmt->fetch()) {
            throw new ProductNotFoundException();
        }
        return $res;
    }

    public function create(array $data): Product
    {
        $prod = Product::createFromArray($data);
        $stmt = $this->db->getConnection()
            ->prepare(
                'INSERT INTO "product" ("name", "price", "productTypeId", "createdAt", "updatedAt") 
                VALUES (?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP) RETURNING "id"'
            );

        $stmt->bindParam(1, $prod->name, PDO::PARAM_STR);
        $stmt->bindParam(2, $prod->price, PDO::PARAM_INT);
        $id = $prod->type->getId();
        $stmt->bindParam(3, $id, PDO::PARAM_INT);

        $stmt->execute();

        ['id' => $id] = $stmt->fetch(PDO::FETCH_ASSOC);

        $prod->setId($id);

        return $prod;
    }
}
