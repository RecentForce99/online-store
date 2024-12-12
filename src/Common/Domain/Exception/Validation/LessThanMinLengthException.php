<?php

declare(strict_types=1);

namespace App\Common\Domain\Exception\Validation;

use Exception;

class LessThanMinLengthException extends Exception
{
    public static function byField(string $fieldName, string|int|float $value, int $minLength): self
    {
        return new self("Длина поля $fieldName [$value] меньше [$minLength] символов.");
    }
}
