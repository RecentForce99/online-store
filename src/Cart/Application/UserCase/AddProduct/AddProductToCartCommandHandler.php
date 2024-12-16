<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\AddProduct;

use App\Common\Infrastructure\Repository\Flusher;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\User\Application\Exception\ProductAlreadyAddedToCartException;
use App\User\Application\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class AddProductToCartCommandHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly Flusher $flusher,
    ) {
    }

    /**
     * @throws ProductAlreadyAddedToCartException
     * @throws UserNotFoundException
     */
    public function __invoke(AddProductToCartCommand $addProductToCartCommand): void
    {
        $user = $this->userRepository->getById($addProductToCartCommand->userId);
        $product = $this->productRepository->getById($addProductToCartCommand->productId);

        $user->addProductToCart($product);
        $this->flusher->flush();
    }
}
