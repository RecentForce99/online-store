<?php

declare(strict_types=1);

namespace App\Auth\Application\Event\Listener;

use App\Auth\Application\Event\AfterUserSignUpEvent;
use App\Auth\Application\UseCase\Create\CreateUserCommand;
use App\Common\Domain\MessageBus\Notification;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEventListener(AfterUserSignUpEvent::class)]
final class AfterUserSignUpEventListener
{
    private const string NOTIFICATION_TYPE = 'sms';

    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(AfterUserSignUpEvent $event): void
    {
        $message = $this->getMessage($event->createUserCommand);
        $message = $this->serializer->serialize($message, 'json');

        $notification = new Notification($message);
        $this->messageBus->dispatch($notification)->last('');
    }

    private function getMessage(CreateUserCommand $createUserCommand): array
    {
        return [
            'promoId' => $createUserCommand->promoId,
            'type' => self::NOTIFICATION_TYPE,
            'userEmail' => $createUserCommand->email,
            'userPhone' => $createUserCommand->phone,
        ];
    }
}
