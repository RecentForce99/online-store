<?php

declare(strict_types=1);

namespace App\Role\Domain\Repository;

use App\Role\Domain\Entity\Role;

interface RoleRepositoryInterface
{
    public function add(Role $role): void;

    public function findBySlug(string $slug): ?Role;

    public function getUserRole(): Role;
}
