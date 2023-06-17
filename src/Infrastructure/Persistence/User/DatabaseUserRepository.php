<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use PDO;
use App\Domain\User\User;
use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\PostgresConnection;


class DatabaseUserRepository implements UserRepository
{
    public function __construct(private PostgresConnection $db)
    {
    }

    public function findAll(): array
    {
        return $this->db->getConnection()
            ->query("SELECT * FROM \"users\" WHERE \"deletedAt\" IS NOT NULL", PDO::FETCH_CLASS, User::class)
            ->fetchAll();
    }

    public function findUserOfId(int $id): User
    {
        $stmt = $this->db->getConnection()
            ->prepare("SELECT * FROM \"users\" WHERE \"id\" = ?");
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
        return $stmt->fetch();
    }
}
