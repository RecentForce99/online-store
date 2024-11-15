<?php

declare(strict_types=1);

namespace App\Presentation\Api;

use App\User\Application\UseCase\SignUp\SignUpUserCommand;
use App\User\Application\UseCase\SignUp\SignUpUserCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/user')]
final class UserController extends AbstractController
{
    #[Route(path: '/crud/add', methods: 'POST')]
    public function signUp(
        #[MapRequestPayload]
        SignUpUserCommand        $signUpUserCommand,
        SignUpUserCommandHandler $signUpUserCommandHandler,
    ): JsonResponse
    {
        return $signUpUserCommandHandler($signUpUserCommand);
    }
}
