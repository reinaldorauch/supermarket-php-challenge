<?php

declare(strict_types=1);

namespace App\Application\Actions\Product;

use Psr\Http\Message\ResponseInterface as Response;

class ListProductsAction extends ProductAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $products = $this->productRepository->findAll();
        $products = array_map(function ($p) {
            $p->type = $this->productTypeRepository->findById($p->type);
            return $p;
        }, $products);

        $this->logger->info("Products list was viewed.");

        return $this->respondWithData($products);
    }
}
