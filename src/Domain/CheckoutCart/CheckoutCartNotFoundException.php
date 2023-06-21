<?php

declare(strict_types=1);

namespace App\Domain\CheckoutCart;

use App\Domain\DomainException\DomainRecordNotFoundException;

class CheckoutCartNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Checkout Cart not found';
}
