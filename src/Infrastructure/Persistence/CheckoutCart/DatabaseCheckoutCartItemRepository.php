<?php

declare(strict_types=1);


namespace App\Infrastructure\Persistence\CheckoutCart;

use App\Domain\CheckoutCart\{CheckoutCart, CheckoutCartItem, CheckoutCartItemRepository, ItemNotFoundException, ItemQuantityOutOfBoundsException};
use PDO;

class DatabaseCheckoutCartItemRepository implements CheckoutCartItemRepository
{
    public function __construct(private PDO $db)
    {
    }

    public function findByCartId(int $id): array
    {
        $stmt = $this->db->prepare(
            'SELECT 
                "checkoutCartId",
                "productId",
                "quantity",
                "chargedPrice",
                "chargedTotalPrice",
                "chargedTax",
                "chargedTotalTax"
            FROM "checkout_cart_product"
            WHERE "deletedAt" IS NULL AND "checkoutCartId" = ?'
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS, CheckoutCartItem::class);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById(int $cartId, int $prodId): CheckoutCartItem
    {
        $stmt = $this->db->prepare(
            'SELECT 
                "checkoutCartId",
                "productId",
                "quantity",
                "chargedPrice",
                "chargedTotalPrice",
                "chargedTax",
                "chargedTotalTax"
            FROM "checkout_cart_product"
            WHERE "deletedAt" IS NULL AND "checkoutCartId" = ? AND "productId" = ?'
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS, CheckoutCartItem::class);
        $stmt->bindParam(1, $cartId, PDO::PARAM_INT);
        $stmt->bindParam(2, $prodId, PDO::PARAM_INT);
        $stmt->execute();

        if (!$ret = $stmt->fetch()) {
            throw new ItemNotFoundException();
        }

        return $ret;
    }

    public function addItemToCart(CheckoutCart $cart, array $data): CheckoutCartItem
    {
        $item = new CheckoutCartItem;
        $item->productId = $data['productId'];
        $item->checkoutCartId = $cart->id;
        $item->quantity = $data['quantity'];

        $stmt =  $this->db->prepare(
            'INSERT INTO "checkout_cart_product" 
                ("productId",
                 "checkoutCartId",
                 "quantity",
                 "chargedPrice",
                 "chargedTotalPrice",
                 "chargedTax",
                 "chargedTotalTax",
                 "createdAt",
                 "updatedAt") 
            VALUES (
                :id, 
                :cartId, 
                CAST(:qty AS SMALLINT), 
                (SELECT "price" FROM "product" WHERE "deletedAt" IS NULL AND "id" = :id),
                (SELECT "price" * :qty FROM "product" 
                  WHERE "deletedAt" IS NULL AND "product"."id" = :id),
                (SELECT ("price" * ("taxRate"::decimal / 100))::bigint FROM "product" "p" 
                  INNER JOIN "product_type" "pt" ON "p"."productTypeId" = "pt"."id" 
                  WHERE "p"."deletedAt" IS NULL AND "p"."id" = :id),
                (SELECT ("price" * ("taxRate"::decimal / 100) * :qty)::bigint FROM "product" "p" 
                  INNER JOIN "product_type" "pt" ON "p"."productTypeId" = "pt"."id" 
                  WHERE "p"."deletedAt" IS NULL AND "p"."id" = :id),
                CURRENT_TIMESTAMP,
                CURRENT_TIMESTAMP
            ) 
            RETURNING 
                "chargedPrice",
                "chargedTotalPrice",
                "chargedTax",
                "chargedTotalTax"'
        );

        $stmt->bindParam('id', $item->productId, PDO::PARAM_INT);
        $stmt->bindParam('qty', $item->quantity, PDO::PARAM_INT);
        $stmt->bindParam('cartId', $item->checkoutCartId, PDO::PARAM_INT);
        $stmt->execute();

        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        foreach ($res as $k => $v) {
            $item->{$k} = $v;
        }

        return $item;
    }

    public function updateItemInCart(CheckoutCart $cart, CheckoutCartItem $item, array $data): void
    {
    }
}
