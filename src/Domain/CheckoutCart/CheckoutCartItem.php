<?php

declare(strict_types=1);

namespace App\Domain\CheckoutCart;

use App\Domain\Product\Product;
use JsonSerializable;

class CheckoutCartItem implements JsonSerializable
{
    public int $checkoutCartId;
    public int $productId;
    public int $quantity;
    public int $chargedPrice;
    public int $chargedTotalPrice;
    public int $chargedTax;
    public int $chargedTotalTax;
    public ?Product $product;

    public function jsonSerialize(): mixed
    {
        return $this;
    }
}
