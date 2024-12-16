<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity\Event\Checkout;

use App\Common\Domain\Event\DomainEventInterface;
use Doctrine\Common\Collections\Collection;

final readonly class AfterOrderCheckoutEvent implements DomainEventInterface
{
    public function __construct(
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
