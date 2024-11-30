<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\AddProduct;

use App\Cart\Domain\Entity\CartProduct;
use App\Cart\Domain\Repository\CartProductRepositoryInterface;
use App\Common\Infrastructure\Repository\Flusher;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\User\Application\Exception\UserNotFound;
use Symfony\Component\Security\Core\User\UserInterface;

final class AddProductToCartCommandHandler
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly CartProductRepositoryInterface $cartProductRepository,
        private readonly Flusher $flusher,
    ) {
    }

    /**
     * @throws UserNotFound
     */
    public function __invoke(UserInterface $user, AddProductToCartCommand $addProductToCartCommand): void
    {
        $product = $this->productRepository->getById($addProductToCartCommand->productId);

        $cartProduct = CartProduct::create(
            user: $user,
            product: $product,
        );

        $this->cartProductRepository->add($cartProduct);
        $this->flusher->flush();
    }
}
