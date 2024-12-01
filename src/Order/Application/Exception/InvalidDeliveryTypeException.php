<?php

declare(strict_types=1);

namespace App\Order\Application\Exception;

use App\Common\Domain\Exception\AbstractPublicRenderedException;

class InvalidDeliveryTypeException extends AbstractPublicRenderedException
{
    public static function bySlug(string $deliveryType): self
    {
        return new self(
            message: "Invalid delivery type [$deliveryType].",
            renderedMessage: "Неверный тип доставки [$deliveryType].",
        );
    }
}
