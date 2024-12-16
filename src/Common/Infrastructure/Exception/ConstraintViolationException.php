<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Exception;

use Exception;

final class ConstraintViolationException extends Exception
{
    public static function byMessage(string $message): self
    {
        return new self($message);
    }
}
