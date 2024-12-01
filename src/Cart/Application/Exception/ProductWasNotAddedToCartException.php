<?php

declare(strict_types=1);

namespace App\Cart\Application\Exception;

use App\Common\Domain\Exception\AbstractPublicRenderedException;

class ProductWasNotAddedToCartException extends AbstractPublicRenderedException
{
    public static function byId(string $id): self
    {
        return new self(
            message: "Product [$id] was not added to the cart.",
            renderedMessage: "Товар [$id] не был добавлен в корзину.",
        );
    }
}
