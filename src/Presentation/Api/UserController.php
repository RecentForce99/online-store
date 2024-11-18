<?php

declare(strict_types=1);

namespace App\Presentation\Api;

use App\User\Application\UseCase\Add\CreateUserCommand;
use App\User\Application\UseCase\Add\CreateUserCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/user')]
final class UserController extends AbstractController
{
    #[Route(methods: 'POST')]
    public function create(
        #[MapRequestPayload]
        CreateUserCommand        $createUserCommand,
        CreateUserCommandHandler $createUserCommandHandler,
    ): JsonResponse
    {
        return $createUserCommandHandler($createUserCommand);
    }
}
