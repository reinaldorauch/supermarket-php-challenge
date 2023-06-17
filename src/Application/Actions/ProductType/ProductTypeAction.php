<?php

declare(strict_types=1);

namespace App\Application\Actions\ProductType;

use App\Application\Actions\Action;
use App\Domain\ProductType\ProductTypeRepository;
use Psr\Log\LoggerInterface;

abstract class ProductTypeAction extends Action
{
    public function __construct(
        LoggerInterface $logger,
        protected ProductTypeRepository $productTypeRepository,
    ) {
        parent::__construct($logger);
    }
}
