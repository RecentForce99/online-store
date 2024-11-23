<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Dto\Fixture\Kafka;

final readonly class ProductMeasurementsForSendingToKafkaDto
{
    public function __construct(
        public int $weight,
        public int $height,
        public int $width,
        public int $length,
    )
    {
    }
}