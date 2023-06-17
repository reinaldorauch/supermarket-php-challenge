<?php

declare(strict_types=1);

namespace App\Application\Actions\CheckoutCart;

use App\Application\Actions\Action;
use App\Domain\CheckoutCart\CheckoutCartRepository;
use App\Domain\Product\ProductRepository;
use App\Domain\ProductType\ProductTypeRepository;
use Psr\Log\LoggerInterface;

abstract class CheckoutCartAction extends Action
{
    public function __construct(
        LoggerInterface $logger,
        protected CheckoutCartRepository $checkoutCartRepository,
        protected ProductRepository $productRepository,
        protected ProductTypeRepository $productTypeRepository,
    ) {
        parent::__construct($logger);
    }
}
