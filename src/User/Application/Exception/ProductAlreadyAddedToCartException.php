<?php

declare(strict_types=1);

namespace App\User\Application\Exception;

use App\Common\Domain\Exception\AbstractPublicRenderedException;

class ProductAlreadyAddedToCartException extends AbstractPublicRenderedException
{
    public static function byId(string $id): self
    {
        return new self(
            message: "Product [$id] already added to the cart.",
            renderedMessage: "Товар [$id] уже добавлен в корзину.",
        );
    }
}
