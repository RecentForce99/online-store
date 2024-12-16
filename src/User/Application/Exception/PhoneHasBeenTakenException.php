<?php

declare(strict_types=1);

namespace App\User\Application\Exception;

use Exception;

class PhoneHasBeenTakenException extends Exception
{
    public static function byPhone(int $phone): self
    {
        return new self("Телефон [$phone] уже занят.");
    }
}
