<?php

declare(strict_types=1);

namespace App\Order\Application\Exception;

use Exception;

class CartIsOverflowingException extends Exception
{
    public static function byCountOfProducts(int $countOfProducts): self
    {
        return new self('Ваша корзина переполнена. Она может содержать не более 20 товаров.'
            . "В вашей корзине на данный момент [$countOfProducts] товаров.");
    }
}
