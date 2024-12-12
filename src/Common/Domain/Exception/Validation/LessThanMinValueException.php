<?php

declare(strict_types=1);

namespace App\Common\Domain\Exception\Validation;

use Exception;

class LessThanMinValueException extends Exception
{
    public static function byField(string $fieldName, string|int|float $value, int $min): self
    {
        return new self("Значение поля $fieldName [$value] меньше минимально допустимого значения [$min].");
    }
}
