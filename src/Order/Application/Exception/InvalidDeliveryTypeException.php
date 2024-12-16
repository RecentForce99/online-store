<?php

declare(strict_types=1);

namespace App\Order\Application\Exception;

use Exception;

class InvalidDeliveryTypeException extends Exception
{
    public static function bySlug(string $deliveryType): self
    {
        return new self("Неверный тип доставки [$deliveryType].");
    }
}
