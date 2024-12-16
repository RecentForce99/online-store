<?php

declare(strict_types=1);

namespace App\Role\Application\Exception;

use Exception;

class RoleNotFoundException extends Exception
{
    public static function byName(string $name): self
    {
        return new self("Роль [$name] не найдена.");
    }
}
