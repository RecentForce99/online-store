<?php

declare(strict_types=1);

namespace App\Order\Application\Exception;

use Exception;

class CartIsEmptyException extends Exception
{
    public static function emptyCart(): self
    {
        return new self('Невозможно оформить заказ. Ваша корзина - пуста.');
    }
}
