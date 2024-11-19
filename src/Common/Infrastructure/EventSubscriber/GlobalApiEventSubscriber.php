<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\EventSubscriber;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * This class <MUST NOT> work for the production environment
 */
final class GlobalApiEventSubscriber implements EventSubscriberInterface
{
    private const PRODUCTION_ENVIRONMENT = 'prod';

    public function __construct(
        private ParameterBagInterface $params,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException'],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $errorDetails = [
            'status' => 'error',
            'errors' => [
                [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                    'line' => $exception->getLine(),
                    'file' => $exception->getFile(),
                    'trace' => $this->formatTrace($exception->getTrace()),
                ],
            ],
        ];

        if (self::PRODUCTION_ENVIRONMENT === $this->params->get('app.env')) {
            $errorDetails['errors'] = [
                'message' => 'Возникло неожиданное исключение.',
            ];
        }

        $response = new JsonResponse(
            data: $errorDetails,
            status: $this->getHttpExceptionCode($exception->getCode()),
        );

        $event->setResponse($response);
    }

    private function getHttpExceptionCode(int $passedCode): int
    {
        $code = $passedCode;
        if (false === isset(Response::$statusTexts[$passedCode])) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return $code;
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
