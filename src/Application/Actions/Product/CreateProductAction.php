<?php

declare(strict_types=1);

namespace App\Application\Actions\Product;

use Psr\Http\Message\ResponseInterface as Response;

class CreateProductAction extends ProductAction
{
    /**
     * {@inheritdoc}
     */
    public function action(): Response
    {
        $data = $this->getFormData();

        $this->logger->debug("Trying to create Product with data `" . json_encode($data));

        $prodType = $this->productTypeRepository->findById($data["type"]);
        $data['type'] = $prodType;
        $product = $this->productRepository->create($data);

        $this->logger->debug('Product got id of' . $product->getId());

        return $this->respondWithData($product);
    }
}
