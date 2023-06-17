<?php

declare(strict_types=1);

namespace App\Domain\CheckoutCart;

interface CheckoutCartItemRepository
{
    /**
     * @return CheckoutCartItem[]
     */
    public function findByCartId(int $id): array;

    /**
     * @return CheckoutCartItem
     */
    public function addItemToCart(CheckoutCart $cart, array $data): CheckoutCartItem;
}
