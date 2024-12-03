<?php

declare(strict_types=1);

namespace App\User\Application\Exception;

use Exception;

class ProductAlreadyAddedToCartException extends Exception
{
    public static function byId(string $id): self
    {
        return new self("Товар [$id] уже добавлен в корзину.");
    }
}
