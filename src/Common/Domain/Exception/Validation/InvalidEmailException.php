<?php

declare(strict_types=1);

namespace App\Common\Domain\Exception\Validation;

use Exception;

class InvalidEmailException extends Exception
{
    public static function byEmail(string $email): self
    {
        return new self("Почта [$email] некорректна.");
    }
}
