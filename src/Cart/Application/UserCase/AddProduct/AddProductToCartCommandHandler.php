<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\AddProduct;

use App\Common\Infrastructure\Repository\Flusher;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\User\Application\Exception\ProductAlreadyAddedToCartException;
use App\User\Domain\Entity\User;

final class AddProductToCartCommandHandler
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly Flusher $flusher,
    ) {
    }

    /**
     * @throws ProductAlreadyAddedToCartException
     */
    public function __invoke(User $user, string $productId): void
    {
        $product = $this->productRepository->getById($productId);

        $user->addProductToCart($product);
        $this->flusher->flush();
    }
}
