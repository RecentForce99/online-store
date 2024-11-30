<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Controller;

use App\Cart\Application\UserCase\AddProduct\AddProductToCartCommand;
use App\Cart\Application\UserCase\AddProduct\AddProductToCartCommandHandler;
use App\Cart\Application\UserCase\List\CartProductListQuery;
use App\Cart\Application\UserCase\List\CartProductListQueryHandler;
use App\User\Application\Exception\UserNotFound;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart')]
final class CartController extends AbstractController
{
    #[Route(methods: ['GET'])]
    public function list(
        #[MapQueryString] ?CartProductListQuery $cartProductListQuery,
        CartProductListQueryHandler $cartProductListQueryHandler,
    ): JsonResponse {
        $user = $this->getUser();
        $queryHandlerResult = $cartProductListQueryHandler(
            $user,
            $cartProductListQuery ?? new CartProductListQuery(),
        );

        return new JsonResponse($queryHandlerResult);
    }

    /**
     * @throws UserNotFound
     */
    #[Route(methods: ['POST'])]
    public function listAddProductToCartCommand(
        #[MapRequestPayload] AddProductToCartCommand $addProductToCartCommand,
        AddProductToCartCommandHandler $addProductToCartCommandHandler,
    ): JsonResponse {
        $user = $this->getUser();
        $queryHandlerResult = $addProductToCartCommandHandler(
            $user,
            $addProductToCartCommand,
        );

        return new JsonResponse($queryHandlerResult);
    }
}
