<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\List;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Uuid;

final class CartProductListQuery
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

        #[Assert\Type(type: 'int')]
        #[Assert\Range(min: 1, max: 100)]
        public int    $limit = 20,

        #[Assert\Type(type: 'int')]
        #[Assert\Range(min: 0)]
        public int    $offset = 0,
    )
    {
    }
}