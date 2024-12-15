<?php

declare(strict_types=1);

namespace App\Product\Domain\Repository;

use App\Product\Domain\Entity\Product;

interface ProductRepositoryInterface
{
    public function findById(string $id): ?Product;

    public function getById(string $id): Product;

    public function add(Product $product): void;

    public function getProductsSoldInLast24Hours(): array;
}
