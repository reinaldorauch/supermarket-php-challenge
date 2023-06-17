<?php

namespace App\Domain\ProductType;

interface ProductTypeRepository
{
    /**
     * @return ProductType[]
     */
    public function findAll(): array;

    /**
     * @return ProductType
     */
    public function findById(int $id): ProductType;

    /**
     * @return ProductType
     */
    public function create(array $data): ProductType;
}
