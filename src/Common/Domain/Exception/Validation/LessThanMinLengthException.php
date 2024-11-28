<?php

declare(strict_types=1);

namespace App\Common\Domain\Exception\Validation;

use App\Common\Domain\Exception\AbstractPublicRenderedException;

class LessThanMinLengthException extends AbstractPublicRenderedException
{
    public static function byEmail(string $fieldName, string $ruFieldName, string|int|float $value, int $minLength): self
    {
        return new self(
            message: "The $fieldName [$value] is less than [$minLength] characters.",
            renderedMessage: "Длина поля $ruFieldName [$value] меньше [$minLength] символов.",
        );
    }
}
