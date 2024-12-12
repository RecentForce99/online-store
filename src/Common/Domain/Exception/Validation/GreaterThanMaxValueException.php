<?php

declare(strict_types=1);

namespace App\Common\Domain\Exception\Validation;

use Exception;

class GreaterThanMaxValueException extends Exception
{
    public static function byField(string $fieldName, string|int|float $value, int $max): self
    {
        return new self("Значение поля $fieldName [$value] больше максимального допустимого значения [$max].");
    }
}
