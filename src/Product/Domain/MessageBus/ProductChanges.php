<?php

declare(strict_types=1);

namespace App\Product\Domain\MessageBus;

final readonly class ProductChanges
{
    public function __construct(
        public string $id,
        public string $name,
        public ProductChangesMeasurements $measurements,
        public string $description,
        public int $cost,
        public int $tax,
        public ?int $version,
    ) {
    }
}
