<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\ChangeQuantityOfProduct;

use App\Cart\Application\Exception\ProductWasNotAddedToCartException;
use App\Common\Infrastructure\Repository\Flusher;
use App\User\Domain\Entity\User;

final class ChangeQuantityOfProductCommandHandler
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
        ChangeQuantityOfProductCommand $changeQuantityOfProductCommand,
        User $user,
    ): void {
        $product = $user->getProductByIdFromCart($productId);

        $user->changeProductQuantityInCart($product, $changeQuantityOfProductCommand->quantity);

        $this->flusher->flush();
    }
}
