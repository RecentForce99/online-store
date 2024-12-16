<?php

declare(strict_types=1);

namespace App\Common\Domain\Exception\Validation;

use Exception;

class WrongLengthOfPhoneNumberException extends Exception
{
    public static function byPhone(int $phone, int $requiredLength): self
    {
        return new self("Длина номера телефона [$phone] должна быть [$requiredLength] символов.");
    }
}
