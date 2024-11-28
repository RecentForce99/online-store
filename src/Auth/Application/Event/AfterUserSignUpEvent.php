<?php

declare(strict_types=1);

namespace App\Auth\Application\Event;

use App\Auth\Application\UseCase\Create\CreateUserCommand;
use Symfony\Contracts\EventDispatcher\Event;

final class AfterUserSignUpEvent extends Event
{
    public function __construct(
        public readonly CreateUserCommand $createUserCommand,
    ) {
    }
}
