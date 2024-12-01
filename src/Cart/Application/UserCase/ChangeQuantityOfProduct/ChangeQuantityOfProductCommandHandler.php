<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\ChangeQuantityOfProduct;

use App\Cart\Application\Exception\ProductWasNotAddedToCartException;
use App\Common\Infrastructure\Repository\Flusher;
use App\Product\Domain\Entity\Product;
use App\User\Domain\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

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
        ChangeQuantityOfProductCommand $changeQuantityOfProductCommand,
        UserInterface $user,
        Product $product,
    ): void {
        $productId = $product->getId()->toString();

        /* @var User $user */
        $cartProduct = $user->findCartProductByProductId($productId);
        if (null === $cartProduct) {
            throw ProductWasNotAddedToCartException::byId($productId);
        }

        $cartProduct->setQuantity($changeQuantityOfProductCommand->quantity);

        $this->flusher->flush();
    }
}
