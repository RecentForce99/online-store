<?php

declare(strict_types=1);

namespace App\Product\Domain\Repository;

use App\Common\Application\Exception\EntityNotFound;
use App\Product\Domain\Entity\Product;

interface ProductRepositoryInterface
{
    public function findById(string $id): ?Product;

    /**
     * @throws EntityNotFound
     */
    public function getById(string $id): Product;

    public function create(Product $product): void;
}
