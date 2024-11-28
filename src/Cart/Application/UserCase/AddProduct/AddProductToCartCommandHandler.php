<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\AddProduct;

use App\Cart\Domain\Entity\CartProduct;
use App\Cart\Domain\Repository\CartProductRepositoryInterface;
use App\Common\Infrastructure\Repository\Flusher;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\User\Application\Exception\UserNotFound;
use App\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Uid\AbstractUid;

final class AddProductToCartCommandHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly CartProductRepositoryInterface $cartProductRepository,
        private readonly AbstractUid $uuid,
        private readonly Flusher $flusher,
    ) {
    }

    /**
     * @throws UserNotFound
     */
    public function __invoke(AddProductToCartCommand $addProductToCartCommand): void
    {
        $currentProduct = $this->productRepository->getById($addProductToCartCommand->productId);
        $currentUser = $this->userRepository->getById($addProductToCartCommand->userId);

        $cartProduct = CartProduct::create(
            user: $currentUser,
            product: $currentProduct,
        );

        $this->cartProductRepository->create($cartProduct);
        $this->flusher->flush();
    }
}
