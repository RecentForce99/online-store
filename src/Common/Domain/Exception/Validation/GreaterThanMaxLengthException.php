<?php

declare(strict_types=1);

namespace App\Common\Domain\Exception\Validation;

use App\Common\Domain\Exception\AbstractPublicRenderedException;

class GreaterThanMaxLengthException extends AbstractPublicRenderedException
{
    public static function byEmail(string $fieldName, string $ruFieldName, string|int|float $value, int $maxLength): self
    {
        return new self(
            message: "The $fieldName [$value] is greater than [$maxLength] characters.",
            renderedMessage: "Длина поля $ruFieldName [$value] больше [$maxLength] символов.",
        );
    }
}
