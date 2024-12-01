<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\AddProduct;

use App\Cart\Domain\Entity\CartProduct;
use App\Cart\Domain\Repository\CartProductRepositoryInterface;
use App\Common\Infrastructure\Repository\Flusher;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\User\Application\Exception\ProductAlreadyAddedToCartException;
use App\User\Application\Exception\UserNotFoundException;
use App\User\Domain\Entity\User;
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
     * @throws UserNotFoundException
     * @throws ProductAlreadyAddedToCartException
     */
    public function __invoke(UserInterface $user, AddProductToCartCommand $addProductToCartCommand): void
    {
        $product = $this->productRepository->getById($addProductToCartCommand->productId);
        $productId = $product->getId()->toString();

        /* @var User $user */
        $user->getCartProducts()->initialize();
        if (true === $this->isProductInCart($user, $productId)) {
            throw ProductAlreadyAddedToCartException::byId($productId);
        }

        $cartProduct = CartProduct::create(
            user: $user,
            product: $product,
        );

        $this->cartProductRepository->add($cartProduct);
        $this->flusher->flush();
    }

    private function isProductInCart(User $user, string $productId): bool
    {
        return null !== $user->findCartProductByProductId($productId);
    }
}
