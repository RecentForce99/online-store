<?php

declare(strict_types=1);

namespace App\Common\Domain\Message;

interface MessageBusInterface
{
    public function publish(string $topic, array $message): void;
}
