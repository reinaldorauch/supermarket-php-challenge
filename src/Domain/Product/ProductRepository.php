<?php

namespace App\Domain\Product;

interface ProductRepository
{
    /**
     * @return Product[]
     */
    public function findAll();

    /**
     * @return Product
     */
    public function findById(int $id): Product;

    /**
     * @return Product
     */
    public function create(array $data): Product;
}
