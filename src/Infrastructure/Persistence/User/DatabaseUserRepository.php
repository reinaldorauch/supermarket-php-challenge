<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use PDO;
use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
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
            ->query("SELECT * FROM \"users\" WHERE \"deletedAt\" IS NULL", PDO::FETCH_CLASS, User::class)
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

    public function findByUsername(string $username): User
    {
        $stmt = $this->db->getConnection()
            ->prepare('SELECT * FROM "users" WHERE "deletedAt" IS NULL AND "username" = ?');
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
        $stmt->bindParam(1, $username, PDO::PARAM_STR);
        $stmt->execute();
        if (!$ret = $stmt->fetch()) {
            throw new UserNotFoundException();
        }
        return $ret;
    }

    public function create(array $data): User
    {
        $passwordHash = password_hash($data['password'], PASSWORD_ARGON2ID);

        $stmt = $this->db->getConnection()
            ->prepare(
                'INSERT INTO "users" 
                    ("username", "firstName", "lastName", "passwordHash", "createdAt", "updatedAt") 
                VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP) RETURNING *'
            );
        $stmt->bindParam(1, $data['username'], PDO::PARAM_STR);
        $stmt->bindParam(2, $data['firstName'], PDO::PARAM_STR);
        $stmt->bindParam(3, $data['lastName'], PDO::PARAM_STR);
        $stmt->bindParam(4, $passwordHash, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
        $stmt->execute();
        return $stmt->fetch();
    }
}
