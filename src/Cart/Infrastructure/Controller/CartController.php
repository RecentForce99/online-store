<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Controller;

use App\Cart\Application\Exception\ProductWasNotAddedToCartException;
use App\Cart\Application\UserCase\AddProduct\AddProductToCartCommand;
use App\Cart\Application\UserCase\AddProduct\AddProductToCartCommandHandler;
use App\Cart\Application\UserCase\CartProductList\CartProductListQuery;
use App\Cart\Application\UserCase\CartProductList\CartProductListQueryHandler;
use App\Cart\Application\UserCase\ChangeQuantityOfProduct\ChangeQuantityOfProductCommand;
use App\Cart\Application\UserCase\ChangeQuantityOfProduct\ChangeQuantityOfProductCommandHandler;
use App\Cart\Application\UserCase\DeleteProductFromCart\DeleteProductFromCartCommandHandler;
use App\Common\Infrastructure\Exception\ConstraintViolationException;
use App\Common\Infrastructure\Trait\FormatConstraintViolationTrait;
use App\User\Application\Exception\ProductAlreadyAddedToCartException;
use App\User\Application\Exception\UserNotFoundException;
use App\User\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/cart')]
final class CartController extends AbstractController
{
    use FormatConstraintViolationTrait;

    /**
     * @throws UserNotFoundException
     * @throws ProductAlreadyAddedToCartException
     */
    #[Route(methods: ['POST'])]
    public function addProductToCartCommand(
        #[MapRequestPayload] AddProductToCartCommand $addProductToCartCommand,
        AddProductToCartCommandHandler $addProductToCartCommandHandler,
    ): JsonResponse {
        /* @var User $user */
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
        /* @var User $user */
        $user = $this->getUser();
        $queryHandlerResult = $cartProductListQueryHandler(
            $user,
            $cartProductListQuery ?? new CartProductListQuery(),
        );

        return new JsonResponse($queryHandlerResult);
    }

    /**
     * @throws ProductWasNotAddedToCartException
     * @throws ConstraintViolationException
     */
    #[Route(path: '/{productId}', methods: ['PATCH'])]
    public function changeQuantityOfProductCommand(
        string $productId,
        ValidatorInterface $validator,
        #[MapRequestPayload] ChangeQuantityOfProductCommand $changeQuantityOfProductCommand,
        ChangeQuantityOfProductCommandHandler $changeQuantityOfProductCommandHandler,
    ): JsonResponse {
        $constraintViolations = $validator->validate(
            $productId,
            new Assert\Uuid(
                message: 'Неверный UUID товара.'
            )
        );
        $this->throwFirstFormattedViolationExceptionIfThereIsOne($constraintViolations);

        /* @var User $user */
        $user = $this->getUser();
        $changeQuantityOfProductCommandHandler(
            $productId,
            $changeQuantityOfProductCommand,
            $user,
        );

        return new JsonResponse();
    }

    /**
     * @throws ProductWasNotAddedToCartException
     * @throws ConstraintViolationException
     */

    #[Route(path: '/{productId}', methods: ['DELETE'])]
    public function deleteProductFromCart(
        string $productId,
        ValidatorInterface $validator,
        DeleteProductFromCartCommandHandler $deleteProductFromCartCommandHandler,
    ): JsonResponse {
        $constraintViolations = $validator->validate(
            $productId,
            new Assert\Uuid(
                message: 'Неверный UUID товара.'
            )
        );
        $this->throwFirstFormattedViolationExceptionIfThereIsOne($constraintViolations);

        /* @var User $user */
        $user = $this->getUser();
        $deleteProductFromCartCommandHandler(
            $productId,
            $user,
        );

        return new JsonResponse();
    }
}
