<?php

declare(strict_types=1);

namespace App\Application\Actions\ProductType;

use Psr\Http\Message\ResponseInterface as Response;

class ListProductTypesAction extends ProductTypeAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $prodList = $this->productTypeRepository->findAll();

        $this->logger->info("ProductTypes list was viewed.");

        return $this->respondWithData($prodList);
    }
}
