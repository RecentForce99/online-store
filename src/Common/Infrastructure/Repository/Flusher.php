<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Repository;

use App\Common\Domain\Repository\FlusherInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class Flusher implements FlusherInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
