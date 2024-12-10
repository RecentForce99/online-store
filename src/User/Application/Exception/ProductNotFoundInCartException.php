<?php

declare(strict_types=1);

namespace App\User\Application\Exception;

use Exception;

class ProductNotFoundInCartException extends Exception
{
    public static function byId(string $id): self
    {
        return new self("Товар [$id] не найден в корзине.");
    }
}
