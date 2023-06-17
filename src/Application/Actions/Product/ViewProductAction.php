<?php

declare(strict_types=1);

namespace App\Application\Actions\Product;

use Psr\Http\Message\ResponseInterface as Response;

class ViewProductAction extends ProductAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $productId = (int) $this->resolveArg('id');
        $product = $this->productRepository->findById($productId);

        $this->logger->info("User of id `" . $productId . "` was viewed.");

        return $this->respondWithData($product);
    }
}
