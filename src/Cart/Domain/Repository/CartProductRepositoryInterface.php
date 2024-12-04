<?php

declare(strict_types=1);

namespace App\Cart\Domain\Repository;

use App\User\Domain\Entity\User;

interface CartProductRepositoryInterface
{
    public function getListWithPaginateForUser(User $user, int $limit, int $offset): array;
}
