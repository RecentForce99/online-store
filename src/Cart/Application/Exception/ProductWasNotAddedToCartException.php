<?php

declare(strict_types=1);

namespace App\Cart\Application\Exception;

use Exception;

class ProductWasNotAddedToCartException extends Exception
{
    public static function byId(string $id): self
    {
        return new self("Товар [$id] не был добавлен в корзину.");
    }
}
