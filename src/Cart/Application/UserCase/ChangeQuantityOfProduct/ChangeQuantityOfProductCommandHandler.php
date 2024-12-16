<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\ChangeQuantityOfProduct;

use App\Cart\Application\Exception\ProductWasNotAddedToCartException;
use App\Common\Infrastructure\Repository\Flusher;
use App\User\Application\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ChangeQuantityOfProductCommandHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly Flusher $flusher,
    ) {
    }

    /**
     * @throws ProductWasNotAddedToCartException
     * @throws UserNotFoundException
     */
    public function __invoke(ChangeQuantityOfProductCommand $changeQuantityOfProductCommand): void
    {
        $user = $this->userRepository->getById($changeQuantityOfProductCommand->userId);
        $product = $user->getProductByIdFromCart($changeQuantityOfProductCommand->productId);

        $user->changeProductQuantityInCart($product, $changeQuantityOfProductCommand->quantity);

        $this->flusher->flush();
    }
}
