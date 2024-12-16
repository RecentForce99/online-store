<?php

declare(strict_types=1);

namespace App\User\Application\Exception;

use Exception;

class EmailHasBeenTakenException extends Exception
{
    public static function byEmail(string $email): self
    {
        return new self("Почта [$email] уже занята.");
    }
}
