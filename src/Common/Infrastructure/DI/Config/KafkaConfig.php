<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\DI\Config;

final class KafkaConfig
{
    public function __construct(
        public readonly array $globalConfig,
        public readonly array $topicConfig,
    ) {
    }
}
