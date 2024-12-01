<?php

declare(strict_types=1);

namespace App\Order\Application\Exception;

use App\Common\Domain\Exception\AbstractPublicRenderedException;

class CartIsEmptyException extends AbstractPublicRenderedException
{
    public static function get(): self
    {
        return new self(
            message: 'Your cart is empty. It is impossible to checkout an order.',
            renderedMessage: 'Невозможно оформить заказ. Ваша корзина - пуста.',
        );
    }
}
