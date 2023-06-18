<?php

declare(strict_types=1);

namespace App\Application\Actions\CheckoutCart;

use App\Domain\CheckoutCart\CheckoutCartRepository;
use Psr\Http\Message\ResponseInterface as Response;


class UpdateProduictCheckoutCartAction extends CheckoutCartAction
{
    public function action(): Response
    {
        $cartId = (int) $this->resolveArg('id');
        $prodId = (int) $this->resolveArg('prodId');
        $data = $this->getFormData();

        $cart = $this->checkoutCartRepository->findById($cartId);
        $item = $this->checkoutCartItemRepository->findById($cartId, $prodId);
        $this->checkoutCartItemRepository->updateItemInCart($cart, $item, $data);

        return $this->success();
    }
}
