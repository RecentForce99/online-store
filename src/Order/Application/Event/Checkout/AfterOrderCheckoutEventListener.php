<?php

declare(strict_types=1);

namespace App\Order\Application\Event\Checkout;

use App\Order\Domain\Entity\Event\Checkout\AfterOrderCheckoutEvent;
use App\Order\Domain\MessageBus\Notification\Checkout\DeliveryAddress;
use App\Order\Domain\MessageBus\Notification\Checkout\OrderCheckedOut;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener(AfterOrderCheckoutEvent::class)]
final class AfterOrderCheckoutEventListener
{
    private const string NOTIFICATION_TYPE = 'email';

    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(AfterOrderCheckoutEvent $event): void
    {
        $notification = new OrderCheckedOut(
            type: self::NOTIFICATION_TYPE,
            userPhone: $event->userPhone,
            userEmail: $event->userEmail,
            notificationType: $event->notificationType,
            orderNum: $event->orderNum,
            orderItems: $event->orderItems,
            deliveryType: $event->deliveryType,
            deliveryAddress: new DeliveryAddress(
                kladrId: $event->deliveryAddress->kladrId,
                fullAddress: $event->deliveryAddress->fullAddress,
            ),
        );

        $this->messageBus->dispatch($notification);
    }
}
