<?php

declare(strict_types=1);

use App\Domain\CheckoutCart\CheckoutCartItemRepository;
use App\Domain\CheckoutCart\CheckoutCartRepository;
use App\Domain\Product\ProductRepository;
use App\Domain\ProductType\ProductTypeRepository;
use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\CheckoutCart\DatabaseCheckoutCartItemRepository;
use App\Infrastructure\Persistence\CheckoutCart\DatabaseCheckoutCartRepository;
use App\Infrastructure\Persistence\Product\DatabaseProductRepository;
use App\Infrastructure\Persistence\ProductType\DatabaseProductTypeRepository;
use App\Infrastructure\Persistence\User\DatabaseUserRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        // UserRepository::class => \DI\autowire(InMemoryUserRepository::class),
        UserRepository::class => \DI\autowire(DatabaseUserRepository::class),
        ProductRepository::class => \DI\autowire(DatabaseProductRepository::class),
        ProductTypeRepository::class => \DI\autowire(DatabaseProductTypeRepository::class),
        CheckoutCartRepository::class => \DI\autowire(DatabaseCheckoutCartRepository::class),
        CheckoutCartItemRepository::class => \DI\autowire(DatabaseCheckoutCartItemRepository::class),
    ]);
};
