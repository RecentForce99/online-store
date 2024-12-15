<?php

declare(strict_types=1);

namespace App\Report\Application\Dto;

final readonly class SoldProductReportDto
{
    public function __construct(
        public string $product_name,
        public int $price,
        public int $amount,
        public SoldProductReportUserDto $user,
    ) {
    }
}
