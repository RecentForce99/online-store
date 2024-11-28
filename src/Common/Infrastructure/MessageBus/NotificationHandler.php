<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\MessageBus;

use App\Common\Domain\MessageBus\Notification;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class NotificationHandler
{
    public function __invoke(Notification $notificationMessage): void
    {
        echo 'Notification';
    }
}
