<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\AddProduct;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Uuid;

final class AddProductToCartCommand
{
    public function __construct(
        #[Assert\NotBlank]
        #[Uuid(
            message: 'Id не соответствует формату',
            strict: true,
            versions: [
                Uuid::V4_RANDOM,
            ],
        )]
        public string $productId,
    ) {
    }
}
