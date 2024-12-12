<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity\Event\Checkout;

final readonly class DeliveryAddress
{
    public function __construct(
        public ?string $kladrId = null,
        public ?string $fullAddress = null,
    ) {
    }
}
