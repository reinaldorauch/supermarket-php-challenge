<?php

declare(strict_types=1);

namespace App\Application\Actions\CheckoutCart;

use App\Application\Actions\ActionPayload;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

class FinalizeCheckoutCartAction extends CheckoutCartAction
{
    public function action(): ResponseInterface
    {
        $tokenData = $this->getTokenData();

        if (!$tokenData) {
            return $this->respond(new ActionPayload(StatusCodeInterface::STATUS_UNAUTHORIZED));
        }

        $cartId = (int) $this->resolveArg('id');

        $this->checkoutCartRepository->finalize($tokenData['sub'], $cartId);

        return $this->respond(new ActionPayload());
    }
}
