<?php

declare(strict_types=1);

namespace App\Domain\CheckoutCart;

class CheckoutCart
{
    /**
     * @var Product[]
     */
    public array $items;

    public int $id;
}
