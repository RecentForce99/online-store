<?php

declare(strict_types=1);

namespace App\Order\Exception;

use Exception;

final class OrderStatusNotFoundException extends Exception
{
    public static function byName(string $name): self
    {
        return new self("Статус заказа [$name] не найден.");
    }
}
