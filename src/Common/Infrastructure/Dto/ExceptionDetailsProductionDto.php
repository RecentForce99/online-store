<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Dto;

final class ExceptionDetailsProductionDto
{
    public function __construct(
        public readonly int $code,
        public readonly string $message,
    ) {
    }
}
