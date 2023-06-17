<?php

declare(strict_types=1);

namespace App\Application\Actions\CheckoutCart;

use Psr\Http\Message\ResponseInterface as Response;

class AddProductCheckoutCartAction extends CheckoutCartAction
{
    public function action(): Response
    {
        $cartId = (int) $this->resolveArg('id');

        $data = $this->getFormData();

        $cart = $this->checkoutCartRepository->findById($cartId);

        $prod = $this->productRepository->findById($data["productId"]);

        $data['productId'] = $prod->getId();
        $cartItem = $this->checkoutCartItemRepository->addItemToCart($cart, $data);

        return $this->respondWithData($cartItem);
    }
}
