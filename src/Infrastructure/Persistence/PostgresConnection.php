<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use PDO;

class PostgresConnection
{
    private PDO $pdoConnection;

    public function __construct(string $dsn)
    {
        $this->pdoConnection = new PDO($dsn, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }

    public function getConnection(): PDO
    {
        return $this->pdoConnection;
    }
}
