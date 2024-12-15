<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Event\Http;

use App\Cart\Application\Exception\ProductWasNotAddedToCartException;
use App\Common\Domain\Exception\Validation\GreaterThanMaxLengthException;
use App\Common\Domain\Exception\Validation\InvalidEmailException;
use App\Common\Domain\Exception\Validation\LessThanMinLengthException;
use App\Common\Domain\Exception\Validation\WrongLengthOfPhoneNumberException;
use App\Common\Infrastructure\Dto\ExceptionDetailsDto;
use App\Common\Infrastructure\Dto\ExceptionDetailsProductionDto;
use App\Common\Infrastructure\Exception\ConstraintViolationException;
use App\Order\Application\Exception\CartIsEmptyException;
use App\Order\Application\Exception\CartIsOverflowingException;
use App\Order\Application\Exception\InvalidDeliveryTypeException;
use App\Order\Exception\OrderStatusNotFoundException;
use App\Order\Infrastructure\Exception\DeliveryTypeNotFoundException;
use App\Role\Application\Exception\RoleNotFoundException;
use App\User\Application\Exception\EmailHasBeenTakenException;
use App\User\Application\Exception\PhoneHasBeenTakenException;
use App\User\Application\Exception\ProductAlreadyAddedToCartException;
use App\User\Application\Exception\UserNotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Throwable;

/**
 * This class <MUST NOT> work for the production environment.
 */
#[AsEventListener(event: KernelEvents::EXCEPTION)]
final class FormatErrorHttpResponseEventListener
{
    private const PRODUCTION_ENVIRONMENT = 'prod';

    public function __construct(
        private LoggerInterface $logger,
        private string $appEnv,
    ) {
    }

    public function getHttpExceptionStatusCodeByExceptionInstance(Throwable $exception): int
    {
        if ($exception instanceof HttpException) {
            return Response::HTTP_UNPROCESSABLE_ENTITY;
        }

        return match ($exception::class) {
            OrderStatusNotFoundException::class,
            DeliveryTypeNotFoundException::class,
            ProductWasNotAddedToCartException::class => Response::HTTP_NOT_FOUND,
            InvalidEmailException::class,
            LessThanMinLengthException::class,
            WrongLengthOfPhoneNumberException::class,
            UserNotFoundException::class,
            RoleNotFoundException::class,
            PhoneHasBeenTakenException::class,
            EmailHasBeenTakenException::class,
            ProductAlreadyAddedToCartException::class,
            CartIsEmptyException::class,
            CartIsOverflowingException::class,
            InvalidDeliveryTypeException::class,
            ConstraintViolationException::class,
            GreaterThanMaxLengthException::class => Response::HTTP_UNPROCESSABLE_ENTITY,
            default => Response::HTTP_INTERNAL_SERVER_ERROR,
        };
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $response = new JsonResponse();

        $exception = $event->getThrowable();
        if ($exception instanceof HandlerFailedException) {
            $exception = $exception->getPrevious();
        }

        $httpStatusCode = $this->getHttpExceptionStatusCodeByExceptionInstance($exception);
        $errorDetails = $this->getErrorDetails($exception);

        self::PRODUCTION_ENVIRONMENT !== $this->appEnv ?: $response->setData('Ошибка сервера.');

        $this->logger->error(
            '[FormatErrorHttpResponseEventListener] an exception occurred while handling a request',
            [
                'message' => $errorDetails,
            ],
        );

        $response->setStatusCode($httpStatusCode);
        $response->setData($errorDetails);

        $event->setResponse($response);
    }

    private function getErrorDetails(Throwable $exception): ExceptionDetailsDto|ExceptionDetailsProductionDto
    {
        if (self::PRODUCTION_ENVIRONMENT === $this->appEnv) {
            return new ExceptionDetailsProductionDto(
                code: $exception->getCode(),
                message: $exception->getMessage(),
            );
        }

        return new ExceptionDetailsDto(
            code: $exception->getCode(),
            message: $exception->getMessage(),
            line: $exception->getLine(),
            file: $exception->getFile(),
            trace: $exception->getTrace(),
        );
    }
}
