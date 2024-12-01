<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\CartProductList;

use App\Cart\Domain\Repository\CartProductRepositoryInterface;
use App\User\Domain\Entity\User;

final class CartProductListQueryHandler
{
    public function __construct(
        private readonly CartProductRepositoryInterface $cartProductRepository,
    ) {
    }

    public function __invoke(User $user, CartProductListQuery $cartProductListQuery): array
    {
        return $this->cartProductRepository->getListWithPaginateForUser(
            user: $user,
            limit: $cartProductListQuery->limit,
            offset: $cartProductListQuery->offset,
        );
    }
}
