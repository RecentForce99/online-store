<?php

declare(strict_types=1);

namespace App\Cart\Application\UserCase\ChangeQuantityOfProduct;

use Symfony\Component\Validator\Constraints as Assert;

final class ChangeQuantityOfProductCommand
{
    #[Assert\NotBlank(
        message: 'Количество товара должно быть обязательно указано.'
    )]
    #[Assert\GreaterThan(
        value: 0,
        message: 'Количество товара в корзине должно быть не меньше 1',
    )]
    #[Assert\Type(
        type: 'int',
        message: 'Количество товара должно быть целым числом.',
    )]
    public int $quantity;

    public function __construct(
        int $quantity,
    ) {
        $this->quantity = $quantity;
    }
}
