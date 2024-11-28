<?php

declare(strict_types=1);

namespace App\Common\Domain\Exception\Validation;

use App\Common\Domain\Exception\AbstractPublicRenderedException;

class InvalidEmailException extends AbstractPublicRenderedException
{
    public static function byEmail(string $email): self
    {
        return new self(
            message: "The email [$email] is invalid.",
            renderedMessage: "Почта [$email] некорректна.",
        );
    }
}
