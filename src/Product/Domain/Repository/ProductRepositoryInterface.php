<?php

declare(strict_types=1);

namespace App\Product\Domain\Repository;

use App\Product\Domain\Entity\Product;
use App\User\Application\Exception\UserNotFound;

interface ProductRepositoryInterface
{
    public function findById(string $id): ?Product;

    /**
     * @throws UserNotFound
     */
    public function getById(string $id): Product;

    public function add(Product $product): void;
}
