<?php

declare(strict_types=1);

namespace App\Auth\Application\Event\Listener;

use App\Auth\Application\Event\AfterUserSignUpEvent;
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
        $createUserCommand = $event->signUpCommand;

        $notification = new Notification(
            type: self::NOTIFICATION_TYPE,
            userEmail: $createUserCommand->email,
            userPhone: $createUserCommand->phone,
            promoId: $createUserCommand->promoId,
        );

        $this->messageBus->dispatch($notification);
    }
}
