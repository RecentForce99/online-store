<?php

declare(strict_types=1);

namespace App\Report\Application\Dto;

final readonly class SoldProductReportUserDto
{
    public function __construct(
        public string $id,
    ) {
    }
}
