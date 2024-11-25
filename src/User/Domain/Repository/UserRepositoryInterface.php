<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\Common\Application\Exception\EntityNotFound;
use App\User\Domain\Entity\User;

interface UserRepositoryInterface
{
    // If it's possible, then replace string with Uuid abstract class
    public function findById(string $id): ?User;

    /**
     * @throws EntityNotFound
     */
    public function getById(string $id): User;

    public function create(User $user): void;
}
