<?php

declare(strict_types=1);

namespace App\Common\Domain\MessageBus;

final readonly class Notification
{
    public function __construct(
        public string $type,
        public string $userEmail,
        public int $userPhone,
        public ?string $promoId,
    ) {
    }
}
