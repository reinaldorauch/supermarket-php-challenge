<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\CheckoutCart;

use App\Domain\CheckoutCart\CheckoutCart;
use App\Domain\CheckoutCart\CheckoutCartRepository;
use App\Infrastructure\Persistence\PostgresConnection;
use PDO;

class DatabaseCheckoutCartRepository implements CheckoutCartRepository
{
    public function __construct(private PostgresConnection $db)
    {
    }

    public function findAll(): array
    {
        return $this->db->getConnection()
            ->query('SELECT "id" FROM "checkout_cart" WHERE "deletedAt" IS NULL', PDO::FETCH_CLASS, CheckoutCart::class)
            ->fetchAll();
    }

    public function findById(int $id): CheckoutCart
    {
        $stmt = $this->db->getConnection()->prepare('SELECT "id" FROM "checkout_cart" WHERE "deletedAt" IS NULL AND "id" = ?');
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, CheckoutCart::class);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create(): CheckoutCart
    {
        return $this->db->getConnection()
            ->query(
                'INSERT INTO "checkout_cart" ("createdAt", "updatedAt") 
                VALUES (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP) RETURNING "id"',
                PDO::FETCH_CLASS,
                CheckoutCart::class
            )
            ->fetch();
    }
}
