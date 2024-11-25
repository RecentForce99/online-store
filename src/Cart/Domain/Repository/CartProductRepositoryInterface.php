<?php

declare(strict_types=1);

namespace App\Cart\Domain\Repository;

use App\Cart\Domain\Entity\CartProduct;
use App\User\Domain\Entity\User;

interface CartProductRepositoryInterface
{
    public function getListWithPaginateForUser(User $user, int $limit, int $offset): array;

    public function findById(string $id): CartProduct;

    public function create(CartProduct $cartProduct): void;
}