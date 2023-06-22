<?php

declare(strict_types=1);

class FinalizeCheckoutCartException extends DomainException
{
    public function __construct(int $userId, int $cartId)
    {
        parent::__construct("Could not finalize the cart # {$cartId} of {$userId}");
    }
}
