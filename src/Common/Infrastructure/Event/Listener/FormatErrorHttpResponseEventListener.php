<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Event\Listener;

use App\Common\Domain\Exception\AbstractPublicRenderedException;
use App\Common\Infrastructure\DI\Config\AppConfig;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

/**
 * This class <MUST NOT> work for the production environment.
 */
#[AsEventListener(event: KernelEvents::EXCEPTION)]
final class FormatErrorHttpResponseEventListener
{
    private const PRODUCTION_ENVIRONMENT = 'prod';

    public function __construct(
        private AppConfig $appConfig,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = new JsonResponse();

        if ($exception instanceof AbstractPublicRenderedException) {
            $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
            $exception->render();
        } elseif ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            self::PRODUCTION_ENVIRONMENT !== $this->appConfig->env
                ?: $response->setData('Ошибка сервера.');
        }

        $errorDetails = $this->getErrorDetails($exception);

        $this->logger->error(
            '[FormatErrorHttpResponseEventListener] an exception occurred while handling a request',
            [
                'message' => $errorDetails,
            ],
        );

        $response->setData($errorDetails);

        $event->setResponse($response);
    }

    private function getErrorDetails(Throwable $exception): array
    {
        $errorDetails = [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
        ];

        if (self::PRODUCTION_ENVIRONMENT !== $this->appConfig->env) {
            $errorDetails = array_merge(
                $errorDetails,
                [
                    'line' => $exception->getLine(),
                    'file' => $exception->getFile(),
                    'trace' => $this->formatTrace($exception->getTrace()),
                ]
            );
        }

        return $errorDetails;
    }

    private function formatTrace(array $trace): array
    {
        $formattedTrace = [];
        foreach ($trace as $traceItem) {
            $formattedTrace[] = [
                'file' => $traceItem['file'] ?? '',
                'line' => $traceItem['line'] ?? 0,
                'function' => $traceItem['function'] ?? '',
                'class' => $traceItem['class'] ?? '',
                'type' => $traceItem['type'] ?? '',
            ];
        }

        return $formattedTrace;
    }
}
