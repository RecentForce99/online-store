<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Dto\Fixture\Kafka;

final readonly class ProductForSendingToKafkaDto
{
    public function __construct(
        public string $id,
        public string $name,
        public ProductMeasurementsForSendingToKafkaDto $measurements,
        public string $description,
        public int $cost,
        public int $tax,
        public ?int $version,
    ) {
    }
}
