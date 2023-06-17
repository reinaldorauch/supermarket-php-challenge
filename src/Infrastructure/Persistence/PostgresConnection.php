<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use PDO;

class PostgresConnection
{
    const POSTGRES_MAX_SMALLINT_VAL = 32767;
    const POSTGRES_MIN_SMALLINT_VAL = -32768;

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
