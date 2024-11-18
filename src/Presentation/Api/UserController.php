<?php

declare(strict_types=1);

namespace App\Presentation\Api;

use App\User\Application\UseCase\Add\AddUserCommand;
use App\User\Application\UseCase\Add\AddUserCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/user')]
final class UserController extends AbstractController
{
    #[Route(methods: 'POST')]
    public function add(
        #[MapRequestPayload]
        AddUserCommand        $addUserCommand,
        AddUserCommandHandler $addUserCommandHandler,
    ): JsonResponse
    {
        return $addUserCommandHandler($addUserCommand);
    }
}
