<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\DeleteProductFromCart;

use App\Cart\Application\Exception\ProductWasNotAddedToCartException;
use App\Cart\Domain\Repository\CartProductRepositoryInterface;
use App\Common\Infrastructure\Repository\Flusher;
use App\Product\Domain\Entity\Product;
use App\User\Domain\Entity\User;

final class DeleteProductFromCartCommandHandler
{
    public function __construct(
        private readonly CartProductRepositoryInterface $cartProductRepository,
        private readonly Flusher $flusher,
    ) {
    }

    /**
     * @throws ProductWasNotAddedToCartException
     */
    public function __invoke(
        User $user,
        Product $product,
    ): void {
        $productId = $product->getId()->toString();

        $cartProduct = $user->findCartProductByProductId($productId);
        if (null === $cartProduct) {
            throw ProductWasNotAddedToCartException::byId($productId);
        }

        $this->cartProductRepository->delete($cartProduct);
        $this->flusher->flush();
    }
}
