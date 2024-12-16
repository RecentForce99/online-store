<?php

declare(strict_types=1);

namespace App\Order\Domain\MessageBus\Notification\Checkout;

use Doctrine\Common\Collections\Collection;

final readonly class OrderCheckedOut
{
    public function __construct(
        public string $type,
        public int $userPhone,
        public string $userEmail,
        public string $notificationType,
        public string $orderNum,
        public Collection $orderItems,
        public string $deliveryType,
        public ?DeliveryAddress $deliveryAddress = null,
    ) {
    }
}
