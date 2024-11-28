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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/cart')]
final class CartController extends AbstractController
{
    /**
     * @throws UserNotFound
     */
    #[Route(methods: ['GET'])]
    public function list(
        Request $request,
        ValidatorInterface $validator,
        CartProductListQueryHandler $cartProductListQueryHandler,
    ): JsonResponse {
        $cartProductListQuery = new CartProductListQuery(
            userId: $request->get('userId', ''),
            offset: (int)$request->get('offset', 0),
            limit: (int)$request->get('limit', 20),
        );

        $errors = $validator->validate($cartProductListQuery);
        if (count($errors) > 0) {
            return new JsonResponse(
                data: [
                    'errors' => $this->getFormattedErrors($errors),
                ],
                status: JsonResponse::HTTP_BAD_REQUEST,
            );
        }

        $queryHandlerResult = $cartProductListQueryHandler($cartProductListQuery);

        return new JsonResponse($queryHandlerResult);
    }

    /**
     * @throws UserNotFound
     */
    #[Route(methods: ['POST'])]
    public function addProductToCart(
        Request $request,
        ValidatorInterface $validator,
        AddProductToCartCommandHandler $addProductToCartCommandHandler,
    ): JsonResponse {
        $addProductToCartCommand = new AddProductToCartCommand(
            userId: $request->get('userId', ''),
            productId: $request->get('productId', ''),
        );

        $errors = $validator->validate($addProductToCartCommand);
        if (count($errors) > 0) {
            return new JsonResponse(
                data: [
                    'errors' => $this->getFormattedErrors($errors),
                ],
                status: JsonResponse::HTTP_BAD_REQUEST,
            );
        }

        $addProductToCartCommandHandler($addProductToCartCommand);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
