<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\AddProduct;

final readonly class AddProductToCartCommand
{
    public function __construct(
        public string $userId,
        public string $productId,
    ) {
    }
}
