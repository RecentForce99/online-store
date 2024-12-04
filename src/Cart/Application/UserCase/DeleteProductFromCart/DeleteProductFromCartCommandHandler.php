<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\DeleteProductFromCart;

use App\Cart\Application\Exception\ProductWasNotAddedToCartException;
use App\Common\Infrastructure\Repository\Flusher;
use App\User\Domain\Entity\User;

final class DeleteProductFromCartCommandHandler
{
    public function __construct(
        private readonly Flusher $flusher,
    ) {
    }

    /**
     * @throws ProductWasNotAddedToCartException
     */
    public function __invoke(
        string $productId,
        User $user,
    ): void {
        $product = $user->getProductByIdFromCart($productId);
        $user->removeProductFromCart($product);

        $this->flusher->flush();
    }
}
