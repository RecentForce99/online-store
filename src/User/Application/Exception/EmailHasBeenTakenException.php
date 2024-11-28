<?php

declare(strict_types=1);

namespace App\User\Application\Exception;

use App\Common\Domain\Exception\AbstractPublicRenderedException;

class EmailHasBeenTakenException extends AbstractPublicRenderedException
{
    public static function byEmail(string $email): self
    {
        return new self(
            message: "The email [$email] has already been taken.",
            renderedMessage: "Почта [$email] уже занята.",
        );
    }
}
