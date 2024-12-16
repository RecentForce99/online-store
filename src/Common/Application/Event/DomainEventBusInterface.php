<?php

declare(strict_types=1);

namespace App\Common\Application\Event;

interface DomainEventBusInterface
{
    public function dispatch(object $event): void;
}
