<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Controller\Api;

use App\User\Application\UseCase\Create\CreateUserCommand;
use App\User\Application\UseCase\Create\CreateUserCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: '/user')]
final class UserController extends AbstractController
{
    #[Route(methods: 'POST')]
    public function create(
        Request                  $request,
        ValidatorInterface       $validator,
        CreateUserCommandHandler $createUserCommandHandler,
    ): JsonResponse
    {
        $createUserCommand = new CreateUserCommand(
            name: $request->get('name', ''),
            email: $request->get('email', ''),
            phone: (int)$request->get('phone', 0),
        );

        $errors = $validator->validate($createUserCommand);
        if (count($errors) > 0) {
            return new JsonResponse(
                data: [
                    'errors' => $this->getFormattedErrors($errors),
                ],
                status: JsonResponse::HTTP_BAD_REQUEST,
            );
        }

        $createUserCommandHandler($createUserCommand);

        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }

    private function getFormattedErrors(ConstraintViolationList $errors): array
    {
        $formattedErrors = [];
        /* @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $fieldSlug = $error->getPropertyPath();
            $formattedErrors[$fieldSlug][] = $error->getMessage();
        }

        return $formattedErrors;
    }
}
