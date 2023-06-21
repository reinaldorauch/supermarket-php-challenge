<?php

declare(strict_types=1);

namespace App\Domain\CheckoutCart;

use Slim\Exception\HttpNotFoundException;

interface CheckoutCartRepository
{
    /**
     * @return CheckoutCart[]
     */
    public function findAll(): array;

    /**
     * @return CheckoutCart
     */
    public function findById(int $id): CheckoutCart;

    /**
     * @throws HttpNotFoundException
     * @return CheckoutCart|null
     */
    public function findByResponsibleUserId(int $id): ?CheckoutCart;

    /**
     * @return CheckoutCart
     */
    public function create(int $userId): CheckoutCart;
}
