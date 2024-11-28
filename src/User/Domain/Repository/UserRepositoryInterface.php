<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Application\Exception\UserNotFound;
use App\User\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function isEmailAvailable(string $email): bool;

    public function isPhoneAvailable(int $phone): bool;

    // If it's possible, then replace string with Uuid abstract class
    public function findById(string $id): ?User;

    /**
     * @throws UserNotFound
     */
    public function getById(string $id): User;

    public function add(User $user): void;
}
