<?php

declare(strict_types=1);

namespace App\Application\Actions\CheckoutCart;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class ViewCurrentCheckoutCartAction extends CheckoutCartAction
{
    public function action(): Response
    {
        $payload = $this->getTokenData();

        if (!$payload) {
            throw new HttpBadRequestException($this->request);
        }

        $userId = (int) $payload['sub'];

        $cart = $this->checkoutCartRepository->findByResponsibleUserId($userId);

        $cart->items = $this->checkoutCartItemRepository->findByCartId($cart->id);
        $cart->items = array_map(function ($i) {
            $i->product = $this->productRepository->findById($i->productId);
            $i->product->type = $this->productTypeRepository->findById($i->product->type);
            return $i;
        }, $cart->items);
        return $this->respondWithData($cart);
    }
}
