<?php

declare(strict_types=1);

namespace App\Application\Actions\CheckoutCart;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpUnauthorizedException;

class CreateCheckoutCartAction extends CheckoutCartAction
{
    public function action(): Response
    {
        $payload = $this->getTokenData();
        if (!$payload or !$payload['sub']) {
            throw new HttpUnauthorizedException($this->request);
        }
        return $this->respondWithData(
            $this->checkoutCartRepository->create((int) $payload['sub']),
            StatusCodeInterface::STATUS_CREATED
        );
    }
}
