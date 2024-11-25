<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\List;

use App\Cart\Domain\Repository\CartProductRepositoryInterface;
use App\Common\Application\Exception\EntityNotFound;
use App\User\Domain\Repository\UserRepositoryInterface;

final class CartProductListQueryHandler
{
    public function __construct(
        private readonly UserRepositoryInterface        $userRepository,
        private readonly CartProductRepositoryInterface $cartProductRepository,

    )
    {
    }

    /**
     * @throws EntityNotFound
     */
    public function __invoke(CartProductListQuery $cartProductListQuery): array
    {
        $currentUser = $this->userRepository->getById($cartProductListQuery->userId);

        return $this->cartProductRepository->getListWithPaginateForUser(
            user: $currentUser,
            limit: $cartProductListQuery->limit,
            offset: $cartProductListQuery->offset,
        );
    }
}