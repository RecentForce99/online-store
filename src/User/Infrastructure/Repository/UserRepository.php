<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\Common\Infrastructure\Repository\AbstractRepository;
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;

final class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function create(User $user): void
    {
        $this->entityManager->persist($user);
    }
}