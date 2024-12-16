<?php

declare(strict_types=1);

namespace App\Report\Domain\MessageBus\SoldProducts;

final readonly class SoldProductsReport
{
    public function __construct(
        public string $reportId,
        public string $result,
        public ?SoldProductsErrorDetails $detail, // This field will only be filled if an exception has occurred
    ) {
    }
}
