<?php

declare(strict_types=1);

namespace App\Product\Domain\MessageBus;

final readonly class ProductChangesMeasurements
{
    public function __construct(
        public int $weight,
        public int $height,
        public int $width,
        public int $length,
    ) {
    }
}
