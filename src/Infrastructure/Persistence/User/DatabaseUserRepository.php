<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use PDO;
use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;


class DatabaseUserRepository implements UserRepository
{
    public function __construct(private PDO $db)
    {
    }

    public function findAll(): array
    {
        return $this->db->query(
            'SELECT "id", "username", "firstName", "lastName", "passwordHash" 
                FROM "users" WHERE "deletedAt" IS NULL',
            PDO::FETCH_CLASS,
            User::class
        )
            ->fetchAll();
    }

    public function findUserOfId(int $id): User
    {
        $stmt = $this->db->prepare(
            'SELECT "id", "username", "firstName", "lastName", "passwordHash" 
                 FROM "users" WHERE "id" = ?'
        );
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
        return $stmt->fetch();
    }

    public function findByUsername(string $username): User
    {
        $stmt = $this->db->prepare(
            'SELECT "id", "username", "firstName", "lastName", "passwordHash" 
                FROM "users" WHERE "deletedAt" IS NULL AND "username" = ?'
        );
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
        $user = User::from(null, $data['username'], $data['firstName'], $data['lastName']);
        $user->setPassword($data['password']);

        $stmt = $this->db->prepare(
            'INSERT INTO "users" 
                ("username", "firstName", "lastName", "passwordHash", "createdAt", "updatedAt") 
            VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP) RETURNING *'
        );
        $stmt->bindParam(1, $user->username, PDO::PARAM_STR);
        $stmt->bindParam(2, $user->firstName, PDO::PARAM_STR);
        $stmt->bindParam(3, $user->lastName, PDO::PARAM_STR);
        $stmt->bindParam(4, $user->passwordHash, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
        $stmt->execute();
        return $stmt->fetch();
    }
}
