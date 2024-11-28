<?php

declare(strict_types=1);

namespace App\User\Application\Exception;

use App\Common\Domain\Exception\AbstractPublicRenderedException;

class UserNotFound extends AbstractPublicRenderedException
{
    public static function byId(string $id): self
    {
        return new self(
            message: "User [$id] not found",
            renderedMessage: "Пользователь [$id] не найден.",
        );
    }
}
