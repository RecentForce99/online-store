<?php

declare(strict_types=1);

namespace App\Order\Application\Exception;

use App\Common\Domain\Exception\AbstractPublicRenderedException;

class CartIsOverflowingException extends AbstractPublicRenderedException
{
    public static function byCountOfProducts(int $countOfProducts): self
    {
        return new self(
            message: "Your cart is overflowing. It can contain no more than 20 products. Your cart has [$countOfProducts] products.",
            renderedMessage: 'Ваша корзина переполнена. Она может содержать не более 20 товаров.'
            . "В вашей корзине на данный момент [$countOfProducts] товаров.",
        );
    }
}
