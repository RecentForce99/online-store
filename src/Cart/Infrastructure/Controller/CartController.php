<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Controller;

use App\Cart\Application\Exception\ProductWasNotAddedToCartException;
use App\Cart\Application\UserCase\AddProduct\AddProductToCartCommand;
use App\Cart\Application\UserCase\CartProductList\CartProductListQuery;
use App\Cart\Application\UserCase\ChangeQuantityOfProduct\ChangeQuantityOfProductCommand;
use App\Cart\Application\UserCase\DeleteProductFromCart\DeleteProductFromCartCommand;
use App\Common\Infrastructure\Exception\ConstraintViolationException;
use App\Common\Infrastructure\Trait\FormatConstraintViolationTrait;
use App\User\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/cart')]
final class CartController extends AbstractController
{
    use FormatConstraintViolationTrait;
    use HandleTrait;

    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @throws ConstraintViolationException
     * @throws ExceptionInterface
     */
    #[Route(path: '/{productId}', methods: ['POST'], name: 'cart.addProductToCart')]
    public function addProductToCart(
        string $productId,
        ValidatorInterface $validator,
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
        $addProductToCartCommand = new AddProductToCartCommand($user->getId()->toString(), $productId);
        $this->messageBus->dispatch($addProductToCartCommand);

        return new JsonResponse();
    }

    #[Route(methods: ['GET'], name: 'cart.productList')]
    public function cartProductList(
        #[MapQueryString] ?CartProductListQuery $cartProductListQuery,
    ): JsonResponse {
        /* @var User $user */
        $user = $this->getUser();

        $cartProductListQuery = $cartProductListQuery ?? new CartProductListQuery();
        $cartProductListQuery->userId = $user->getId()->toString();

        return new JsonResponse($this->handle($cartProductListQuery));
    }

    /**
     * @throws ProductWasNotAddedToCartException
     * @throws ConstraintViolationException
     * @throws ExceptionInterface
     */
    #[Route(path: '/{productId}', methods: ['PATCH'], name: 'cart.changeQuantityOfProduct')]
    public function changeQuantityOfProduct(
        string $productId,
        ValidatorInterface $validator,
        #[MapRequestPayload] ChangeQuantityOfProductCommand $changeQuantityOfProductCommand,
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

        $changeQuantityOfProductCommand->userId = $user->getId()->toString();
        $changeQuantityOfProductCommand->productId = $productId;

        $this->messageBus->dispatch($changeQuantityOfProductCommand);

        return new JsonResponse();
    }

    /**
     * @throws ConstraintViolationException
     * @throws ExceptionInterface
     */
    #[Route(path: '/{productId}', methods: ['DELETE'], name: 'cart.deleteProductFromCart')]
    public function deleteProductFromCart(
        string $productId,
        ValidatorInterface $validator,
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
        $deleteProductFromCartCommand = new DeleteProductFromCartCommand(
            $user->getId()->toString(),
            $productId,
        );

        $this->messageBus->dispatch($deleteProductFromCartCommand);

        return new JsonResponse();
    }
}
