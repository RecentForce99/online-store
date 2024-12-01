<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Controller;

use App\Cart\Application\Exception\ProductWasNotAddedToCartException;
use App\Cart\Application\UserCase\AddProduct\AddProductToCartCommand;
use App\Cart\Application\UserCase\AddProduct\AddProductToCartCommandHandler;
use App\Cart\Application\UserCase\ChangeQuantityOfProduct\ChangeQuantityOfProductCommand;
use App\Cart\Application\UserCase\ChangeQuantityOfProduct\ChangeQuantityOfProductCommandHandler;
use App\Cart\Application\UserCase\DeleteProductFromCart\DeleteProductFromCartCommandHandler;
use App\Cart\Application\UserCase\CartProductList\CartProductListQuery;
use App\Cart\Application\UserCase\CartProductList\CartProductListQueryHandler;
use App\Product\Domain\Entity\Product;
use App\User\Application\Exception\ProductAlreadyAddedToCartException;
use App\User\Application\Exception\UserNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart')]
final class CartController extends AbstractController
{
    /**
     * @throws UserNotFoundException
     * @throws ProductAlreadyAddedToCartException
     */
    #[Route(methods: ['POST'])]
    public function addProductToCartCommand(
        #[MapRequestPayload] AddProductToCartCommand $addProductToCartCommand,
        AddProductToCartCommandHandler $addProductToCartCommandHandler,
    ): JsonResponse {
        $user = $this->getUser();
        $addProductToCartCommandHandler(
            $user,
            $addProductToCartCommand,
        );

        return new JsonResponse();
    }

    #[Route(methods: ['GET'])]
    public function cartProductList(
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
     * @throws ProductWasNotAddedToCartException
     */
    #[Route(path: '/{product}', methods: ['PATCH'])]
    public function changeQuantityOfProductCommand(
        Product $product,
        #[MapRequestPayload] ChangeQuantityOfProductCommand $changeQuantityOfProductCommand,
        ChangeQuantityOfProductCommandHandler $changeQuantityOfProductCommandHandler,
    ): JsonResponse {
        $user = $this->getUser();
        $changeQuantityOfProductCommandHandler(
            $changeQuantityOfProductCommand,
            $user,
            $product,
        );

        return new JsonResponse();
    }

    /**
     * @throws ProductWasNotAddedToCartException
     */
    #[Route(path: '/{product}', methods: ['DELETE'])]
    public function DeleteProductFromCart(
        Product $product,
        DeleteProductFromCartCommandHandler $deleteProductFromCartCommandHandler,
    ): JsonResponse {
        $user = $this->getUser();
        $deleteProductFromCartCommandHandler(
            $user,
            $product,
        );

        return new JsonResponse();
    }
}
