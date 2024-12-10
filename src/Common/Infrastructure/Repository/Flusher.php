<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Repository;

use App\Common\Application\Event\DomainEventBusInterface;
use App\Common\Domain\Entity\AbstractBaseEntity;
use App\Common\Domain\Repository\FlusherInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class Flusher implements FlusherInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DomainEventBusInterface $domainEventBus,
    ) {
    }

    public function flush(): void
    {
        $domainEvents = $this->getDomainEvents();

        $this->entityManager->flush();

        $this->releaseDomainEvents($domainEvents);
    }

    private function getDomainEvents(): array
    {
        $domainEvents = [];
        foreach ($this->entityManager->getUnitOfWork()->getIdentityMap() as $entities) {
            foreach ($entities as $entity) {
                if ($entity instanceof AbstractBaseEntity) {
                    $entityEvents = $entity->releaseDomainEvents();
                    $domainEvents = array_merge($domainEvents, $entityEvents);
                }
            }
        }

        return $domainEvents;
    }

    private function releaseDomainEvents(array $domainEvents): void
    {
        foreach ($domainEvents as $event) {
            $this->domainEventBus->dispatch($event);
        }
    }
}
