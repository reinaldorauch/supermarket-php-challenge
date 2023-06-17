<?php

declare(strict_types=1);

namespace App\Application\Actions\ProductType;

use Psr\Http\Message\ResponseInterface as Response;

class CreateProductTypeAction extends ProductTypeAction
{
    /**
     * {@inheritdoc}
     */
    public function action(): Response
    {
        $data = $this->getFormData();

        $this->logger->debug("Trying to create ProductType with data `" . json_encode($data));

        $prodType = $this->productTypeRepository->create($data);

        $this->logger->debug('Product got id of' . $prodType->getId());

        return $this->respondWithData($prodType);
    }
}
