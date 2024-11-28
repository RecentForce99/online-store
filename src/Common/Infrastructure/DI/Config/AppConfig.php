<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\DI\Config;

final class AppConfig
{
    public function __construct(
        public readonly string $env,
        public readonly string $domain,
        public readonly string $scheme,
    ) {
    }
}
