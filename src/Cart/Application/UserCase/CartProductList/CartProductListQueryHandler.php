<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\CartProductList;

use App\Cart\Domain\Repository\CartProductRepositoryInterface;
use App\User\Application\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CartProductListQueryHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly CartProductRepositoryInterface $cartProductRepository,
    ) {
    }

    /**
     * @throws UserNotFoundException
     */
    public function __invoke(CartProductListQuery $cartProductListQuery): array
    {
        $user = $this->userRepository->getById($cartProductListQuery->userId);

        return $this->cartProductRepository->getListWithPaginateForUser(
            user: $user,
            limit: $cartProductListQuery->limit,
            offset: $cartProductListQuery->offset,
        );
    }
}
