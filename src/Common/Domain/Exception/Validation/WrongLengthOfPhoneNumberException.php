<?php

declare(strict_types=1);

namespace App\Common\Domain\Exception\Validation;

use App\Common\Domain\Exception\AbstractPublicRenderedException;

class WrongLengthOfPhoneNumberException extends AbstractPublicRenderedException
{
    public static function byPhone(int $phone, int $requiredLength): self
    {
        return new self(
            message: "The length of the phone number [$phone] must be [$requiredLength] characters.",
            renderedMessage: "Длина номера телефона [$phone] должна быть [$requiredLength] символов.",
        );
    }
}
