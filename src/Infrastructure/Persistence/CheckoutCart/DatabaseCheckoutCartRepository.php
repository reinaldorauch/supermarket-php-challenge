<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\CheckoutCart;

use App\Application\Domain\CheckoutCart\FinalizeCheckoutCartException;
use App\Domain\CheckoutCart\CheckoutCart;
use App\Domain\CheckoutCart\CheckoutCartNotFoundException;
use App\Domain\CheckoutCart\CheckoutCartRepository;
use PDO;
use PDOStatement;

class DatabaseCheckoutCartRepository implements CheckoutCartRepository
{
    public function __construct(private PDO $db)
    {
    }

    public function findAll(): array
    {
        return $this->db->query(
            'SELECT "id", "createdBy", "responsibleUserId" 
            FROM "checkout_cart"
            WHERE "finalizedAt" IS NULL
                AND "canceledAt" IS NULL ',
            PDO::FETCH_CLASS,
            CheckoutCart::class
        )
            ->fetchAll();
    }

    public function findById(int $id): CheckoutCart
    {
        $stmt = $this->db->prepare(
            'SELECT "id", "createdBy", "responsibleUserId" 
            FROM "checkout_cart" 
            WHERE "finalizedAt" IS NULL
                AND "canceledAt" IS NULL AND "id" = ?'
        );
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, CheckoutCart::class);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function findByResponsibleUserId(int $userId): ?CheckoutCart
    {
        $stmt = $this->db->prepare(
            'SELECT "id", "createdBy", "responsibleUserId" 
            FROM "checkout_cart" 
            WHERE "finalizedAt" IS NULL
                AND "canceledAt" IS NULL
                AND "responsibleUserId" = ?'
        );
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, CheckoutCart::class);
        $stmt->execute();

        if ($res = $stmt->fetch()) {
            return $res;
        }

        throw new CheckoutCartNotFoundException();
    }

    public function create(int $userId): CheckoutCart
    {
        $stmt = $this->db->prepare(
            'INSERT INTO "checkout_cart" ("createdBy", "responsibleUserId", "createdAt", "updatedAt") 
                VALUES (:userId, :userId, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP) 
                RETURNING "id", "createdBy", "responsibleUserId"',
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS, CheckoutCart::class);
        $stmt->bindParam('userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function finalize(int $userId, int $cartId): void
    {
        /** @var PDOStatement */
        $stmt = $this->db->prepare(
            'UPDATE "checkout_cart" 
            SET "finalizedAt" = CURRENT_TIMESTAMP 
            WHERE "id" = ? 
                AND "responsibleUserId" = ?
                AND "finalizedAt" IS NULL 
                AND "canceledAt" IS NULL'
        );
        $stmt->bindParam(1, $cartId, PDO::PARAM_INT);
        $stmt->bindParam(2, $userId, PDO::PARAM_INT);
        $stmt->execute();
        if (1 !== $stmt->rowCount()) {
            $this->db->query('ROLLBACK');
            throw new FinalizeCheckoutCartException($userId, $cartId);
        }
    }
}
