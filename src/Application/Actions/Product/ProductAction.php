<?php

declare(strict_types=1);

namespace App\Application\Actions\Product;

use App\Application\Actions\Action;
use App\Domain\Product\ProductRepository;
use App\Domain\ProductType\ProductTypeRepository;
use Psr\Log\LoggerInterface;

abstract class ProductAction extends Action
{
    public function __construct(
        LoggerInterface $logger,
        protected ProductRepository $productRepository,
        protected ProductTypeRepository $productTypeRepository,
    ) {
        parent::__construct($logger);
    }
}
