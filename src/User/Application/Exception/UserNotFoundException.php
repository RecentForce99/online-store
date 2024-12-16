<?php

declare(strict_types=1);

namespace App\User\Application\Exception;

use Exception;

class UserNotFoundException extends Exception
{
    public static function byId(string $id): self
    {
        return new self("Пользователь [$id] не найден.");
    }
}
