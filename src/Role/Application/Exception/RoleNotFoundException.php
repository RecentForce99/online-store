<?php

declare(strict_types=1);

namespace App\Role\Application\Exception;

use Exception;

class RoleNotFoundException extends Exception
{
    public static function bySlug(string $slug, ?string $name = null): self
    {
        if (true === is_null($name)) {
            $name = $slug;
        }

        return new self("Роль [$name] не найдена.");
    }
}
