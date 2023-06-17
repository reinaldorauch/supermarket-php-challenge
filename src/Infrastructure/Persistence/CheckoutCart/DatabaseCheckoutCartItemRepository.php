<?php

declare(strict_types=1);


namespace App\Infrastructure\Persistence\CheckoutCart;

use App\Domain\CheckoutCart\{CheckoutCart, CheckoutCartItem, CheckoutCartItemRepository, ItemQuantityOutOfBoundsException};
use App\Infrastructure\Persistence\PostgresConnection;
use PDO;

class DatabaseCheckoutCartItemRepository implements CheckoutCartItemRepository
{
    public function __construct(private PostgresConnection $db)
    {
    }

    public function findByCartId(int $id): array
    {
        $stmt = $this->db->getConnection()
            ->prepare(
                'SELECT 
                    "checkoutCartId",
                    "productId",
                    "quantity",
                    "chargedPrice",
                    "chargedTotalPrice",
                    "chargedTax",
                    "chargedTotalTax"
                FROM "checkout_cart_product"
                WHERE "deletedAt" IS NOT NULL AND "checkoutCartId" = ?'
            );
        $stmt->setFetchMode(PDO::FETCH_CLASS, CheckoutCartItem::class);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function addItemToCart(CheckoutCart $cart, array $data): CheckoutCartItem
    {
        $item = new CheckoutCartItem;
        $item->productId = $data['productId'];
        $item->checkoutCartId = $cart->id;
        $item->quantity = $data['quantity'];

        if ($item->quantity > PostgresConnection::POSTGRES_MAX_SMALLINT_VAL or $item->quantity < PostgresConnection::POSTGRES_MIN_SMALLINT_VAL) {
            throw new ItemQuantityOutOfBoundsException();
        }

        $stmt =  $this->db->getConnection()
            ->prepare(
                'INSERT INTO "checkout_cart_product" 
                    ("productId", "checkoutCartId", "quantity",  "chargedPrice", "chargedTotalPrice", "chargedTax", "chargedTotalTax") 
                VALUES (
                    :id, 
                    :cartId, 
                    CAST(:qty AS SMALLINT), 
                    (SELECT "price" FROM "product" WHERE "deletedAt" IS NULL AND "id" = :id),
                    (SELECT "price" * :qty FROM "product" WHERE "deletedAt" IS NULL AND "product"."id" = :id),
                    (SELECT "price" * ("taxRate" / 100) FROM "product" "p" INNER JOIN "product_type" "pt" ON "p"."productTypeId" = "pt"."id" WHERE "p"."deletedAt" IS NULL AND "p"."id" = :id),
                    (SELECT "price" * ("taxRate" / 100) * :qty FROM "product" "p" INNER JOIN "product_type" "pt" ON "p"."productTypeId" = "pt"."id" WHERE "p"."deletedAt" IS NULL AND "p"."id" = :id)
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
}
