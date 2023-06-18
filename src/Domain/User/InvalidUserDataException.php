<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\DomainException\InvalidDataException;

class InvalidUserDataException extends InvalidDataException
{
    public $message = 'Invalid user data';
}
