<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Exception;

use Exception;

final class DeliveryTypeNotFoundException extends Exception
{
    public static function byName(string $name): self
    {
        return new self("Тип доставки [$name] не найден");
    }
}
