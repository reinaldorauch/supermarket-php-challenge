<?php

declare(strict_types=1);

namespace App\Application\Actions\CheckoutCart;

use Psr\Http\Message\ResponseInterface as Response;

class ViewCheckoutCartAction extends CheckoutCartAction
{
    public function action(): Response
    {
        $cartId = (int) $this->resolveArg('id');
        $cart = $this->checkoutCartRepository->findById($cartId);
        $cart->items = $this->checkoutCartItemRepository->findByCartId($cartId);
        return $this->respondWithData($cart);
    }
}
