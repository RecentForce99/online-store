<?php

declare(strict_types=1);

namespace App\Common\Domain\Entity;

use App\Common\Domain\Event\DomainEventInterface;

abstract class AbstractBaseEntity
{
    private array $domainEvents = [];

    protected function __construct()
    {
    }

    final public function releaseDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    final protected function recordEvent(DomainEventInterface $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }
}
