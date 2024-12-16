<?php

declare(strict_types=1);

namespace App\Report\Domain\MessageBus\SoldProducts;

final readonly class SoldProductsErrorDetails
{
    public function __construct(
        public string $error,
        public ?string $message,
    ) {
    }
}
