<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\DeleteProductFromCart;

final readonly class DeleteProductFromCartCommand
{
    public function __construct(
        public string $userId,
        public string $productId,
    ) {
    }
}
