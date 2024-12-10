<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Event;

use App\Common\Application\Event\DomainEventBusInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly class DomainEventBus implements DomainEventBusInterface
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function dispatch(object $event): void
    {
        $this->eventDispatcher->dispatch($event);
    }
}
