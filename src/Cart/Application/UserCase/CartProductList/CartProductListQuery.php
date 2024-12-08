<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\CartProductList;

use Symfony\Component\Validator\Constraints as Assert;

final class CartProductListQuery
{
    #[Assert\Type('numeric')]
    #[Assert\Range(min: 1, max: 100)]
    public int $limit = 20;

    #[Assert\Type('numeric')]
    #[Assert\Range(min: 0)]
    public int $offset = 0;
    public string $userId; // This param is set from code
}
