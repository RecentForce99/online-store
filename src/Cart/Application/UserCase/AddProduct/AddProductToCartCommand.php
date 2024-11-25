<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\AddProduct;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Uuid;

final class AddProductToCartCommand
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid(
            message: 'Id не соответствует формату',
            strict: true,
            versions: [
                Uuid::V4_RANDOM,
            ],
        )]
        public string $userId,

        #[Assert\NotBlank]
        #[Assert\Uuid(
            message: 'Id не соответствует формату',
            strict: true,
            versions: [
                Uuid::V4_RANDOM,
            ],
        )]
        public string $productId,
    )
    {
    }
}