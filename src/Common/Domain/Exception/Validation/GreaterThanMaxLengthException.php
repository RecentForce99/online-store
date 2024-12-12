<?php

declare(strict_types=1);

namespace App\Common\Domain\Exception\Validation;

use Exception;

class GreaterThanMaxLengthException extends Exception
{
    public static function byField(string $fieldName, string|int|float $value, int $maxLength): self
    {
        return new self("Длина поля $fieldName [$value] больше [$maxLength] символов.");
    }
}
