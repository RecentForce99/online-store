<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\Create;

use App\Common\Domain\Message\MessageBusInterface;

final readonly class SendNotificationCommandHandler
{
    private const string TYPE = 'sms';

    public function __construct(
        private MessageBusInterface $messageBus,
    )
    {
    }

    public function __invoke(CreateUserCommand $createUserCommand, string $topic): void
    {
        $this->messageBus->publish(
            $topic,
            $this->getMessage($createUserCommand),
        );
    }

    private function getMessage(CreateUserCommand $createUserCommand): array
    {
        return [
            'promoId' => $createUserCommand->promoId,
            'type' => self::TYPE,
            'userEmail' => $createUserCommand->email,
            'userPhone' => $createUserCommand->phone,
        ];
    }
}