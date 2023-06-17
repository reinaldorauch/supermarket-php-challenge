<?php

declare(strict_types=1);

namespace App\Domain\CheckoutCart;

interface CheckoutCartRepository
{
    /**
     * @return CheckoutCart[]
     */
    public function findAll(): array;

    /**
     * @return CheckoutCart
     */
    public function findById(int $id): CheckoutCart;

    /**
     * @return CheckoutCart
     */
    public function create(): CheckoutCart;
}
