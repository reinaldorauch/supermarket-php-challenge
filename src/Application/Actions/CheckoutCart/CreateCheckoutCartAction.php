<?php

declare(strict_types=1);

namespace App\Application\Actions\CheckoutCart;

use Psr\Http\Message\ResponseInterface as Response;

class CreateCheckoutCartAction extends CheckoutCartAction
{
    public function action(): Response
    {
        return $this->respondWithData($this->checkoutCartRepository->create());
    }
}
