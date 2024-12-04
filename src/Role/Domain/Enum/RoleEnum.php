<?php

declare(strict_types=1);

namespace App\Role\Domain\Enum;

enum RoleEnum: string
{
    case ROLE_ADMIN = 'Администратор';
    case ROLE_USER = 'Пользователь';
}
