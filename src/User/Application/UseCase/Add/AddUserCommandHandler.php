<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\Add;

use App\Common\Domain\Exception\Validation\GreaterThanMaxLengthException;
use App\Common\Domain\Exception\Validation\InvalidEmailException;
use App\Common\Domain\Exception\Validation\LessThanMinLengthException;
use App\Common\Domain\Exception\Validation\WrongLengthOfPhoneNumberException;
use App\Common\Domain\ValueObject\Email;
use App\Common\Domain\ValueObject\RuPhoneNumber;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Name;
use App\User\Infrastructure\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

final class AddUserCommandHandler
{
    public function __construct(
        private readonly UserRepository $userRepository,
    )
    {
    }

    public function __invoke(AddUserCommand $addUserCommand): JsonResponse
    {
        $user = User::add(
            name: Name::fromString($addUserCommand->name),
            email: Email::fromString($addUserCommand->email),
            phone: RuPhoneNumber::fromInt($addUserCommand->phone),
        );

        $this->userRepository->add($user);

        return new JsonResponse();
    }
}