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
    public function findById(int $cartId, int $prodId): CheckoutCartItem;

    /**
     * @return CheckoutCartItem
     */
    public function addItemToCart(CheckoutCart $cart, array $data): CheckoutCartItem;

    /**
     * @return void
     */
    public function updateItemInCart(CheckoutCart $cart, CheckoutCartItem $item, array $data): void;
}
