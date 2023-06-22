<?php

declare(strict_types=1);

namespace App\Domain\CheckoutCart;

use JsonSerializable;

class CheckoutCart implements JsonSerializable
{
    /**
     * @var CheckoutCartItem[]
     */
    public array $items = [];

    public int $id;

    public int $responsibleUserId;

    public int $createdBy;

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'items' => array_map(fn ($i) => $i->jsonSerialize(), $this->items),
            'totalTax' => array_reduce($this->items, fn ($acc, $i) => $acc + $i->chargedTotalTax, 0),
            'total' => array_reduce(
                $this->items,
                fn ($acc, $i) => $acc + $i->chargedTotalPrice + $i->chargedTotalTax,
                0
            )
        ];
    }
}
