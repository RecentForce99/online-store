<?php

declare(strict_types=1);

namespace App\User\Application\Exception;

use App\Common\Domain\Exception\AbstractPublicRenderedException;

class PhoneHasBeenTakenException extends AbstractPublicRenderedException
{
    public static function byPhone(int $phone): self
    {
        return new self(
            message: "The phone [$phone] has already been taken.",
            renderedMessage: "Телефон [$phone] уже занят.",
        );
    }
}
