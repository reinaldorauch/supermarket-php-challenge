<?php

declare(strict_types=1);

namespace App\Domain\CheckoutCart;

use App\Domain\DomainException\DomainRecordNotFoundException;

class ItemNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'This item was not found in this cart';
}
