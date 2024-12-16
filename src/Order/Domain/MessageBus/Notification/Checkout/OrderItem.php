<?php

declare(strict_types=1);

namespace App\Order\Domain\MessageBus\Notification\Checkout;

final readonly class OrderItem
{
    public function __construct(
        public string $name,
        public int $cost,
        public ?string $additionalInfo = null,
    ) {
    }
}
