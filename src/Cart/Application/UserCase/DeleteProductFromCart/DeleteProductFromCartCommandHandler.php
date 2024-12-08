<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\DeleteProductFromCart;

use App\Cart\Application\Exception\ProductWasNotAddedToCartException;
use App\Common\Infrastructure\Repository\Flusher;
use App\User\Application\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class DeleteProductFromCartCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private Flusher $flusher,
    ) {
    }

    /**
     * @throws ProductWasNotAddedToCartException
     * @throws UserNotFoundException
     */
    public function __invoke(DeleteProductFromCartCommand $deleteProductFromCartCommand): void
    {
        $user = $this->userRepository->getById($deleteProductFromCartCommand->userId);

        $product = $user->getProductByIdFromCart($deleteProductFromCartCommand->productId);
        $user->removeProductFromCart($product);

        $this->flusher->flush();
    }
}
