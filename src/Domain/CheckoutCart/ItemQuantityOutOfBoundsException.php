<?php

declare(strict_types=1);

namespace App\Domain\CheckoutCart;

use App\Domain\DomainException\InvalidDataException;
use App\Infrastructure\Persistence\PostgresConnection;

class ItemQuantityOutOfBoundsException extends InvalidDataException
{
    public $message = 'Item cannot have a quantity more than '
        . PostgresConnection::POSTGRES_MAX_SMALLINT_VAL . ' or less than '
        . PostgresConnection::POSTGRES_MIN_SMALLINT_VAL;
}
