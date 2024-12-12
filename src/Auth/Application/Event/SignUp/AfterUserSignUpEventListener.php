<?php

declare(strict_types=1);

namespace App\Auth\Application\Event\SignUp;

use App\Auth\Application\Domain\Event\SignUp\AfterUserSignUpEvent;
use App\Common\Domain\MessageBus\Notification;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener(AfterUserSignUpEvent::class)]
final class AfterUserSignUpEventListener
{
    private const string NOTIFICATION_TYPE = 'sms';

    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(AfterUserSignUpEvent $event): void
    {
        $notification = new Notification(
            type: self::NOTIFICATION_TYPE,
            userEmail: $event->email,
            userPhone: $event->phone,
            promoId: $event->promoId,
        );

        $this->messageBus->dispatch($notification);
    }
}
