<?php

declare(strict_types=1);

namespace App\Role\Application\Exception;

use App\Common\Domain\Exception\AbstractPublicRenderedException;

class RoleNotFound extends AbstractPublicRenderedException
{
    public static function bySlug(string $slug, string $name = null): self
    {
        if (true === is_null($name)) {
            $name = $slug;
        }

        return new self(
            message: "Role [$slug] not found",
            renderedMessage: "Роль [$name] не найдена.",
        );
    }
}
