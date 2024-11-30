<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\List;

use App\Cart\Domain\Repository\CartProductRepositoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class CartProductListQueryHandler
{
    public function __construct(
        private readonly CartProductRepositoryInterface $cartProductRepository,
    ) {
    }

    public function __invoke(UserInterface $user, CartProductListQuery $cartProductListQuery): array
    {
        return $this->cartProductRepository->getListWithPaginateForUser(
            user: $user,
            limit: $cartProductListQuery->limit,
            offset: $cartProductListQuery->offset,
        );
    }
}
