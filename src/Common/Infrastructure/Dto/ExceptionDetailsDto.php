<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Dto;

final class ExceptionDetailsDto
{
    public function __construct(
        public readonly int $code,
        public readonly string $message,
        public readonly int $line,
        public readonly string $file,
        public readonly array $trace,
    ) {
    }
}
