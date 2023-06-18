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

        // If no cart, throws and returns 404
        $cart = $this->checkoutCartRepository->findById($cartId);

        // If no product, throws and returns 404
        $prod = $this->productRepository->findById($data["productId"]);

        $cartItem = $this->checkoutCartItemRepository->addItemToCart($cart, [
            'productId' => $prod->getId(),
            'quantity' => $data['quantity']
        ]);

        return $this->respondWithData($cartItem);
    }
}
