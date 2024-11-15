<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class GlobalApiEventSubscriber implements EventSubscriberInterface
{
    private const PRODUCTION_ENVIRONMENT = 'prod';

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['onKernelResponse'],
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
                    'code' => method_exists($exception, 'getCode') ? $exception->getCode() : 0,
                    'message' => $exception->getMessage(),
                    'line' => $exception->getLine(),
                    'file' => $exception->getFile(),
                    'trace' => $this->formatTrace($exception->getTrace()),
                ],
            ],
        ];

        if (self::PRODUCTION_ENVIRONMENT === getenv('APP_ENV')) {
            $errorDetails['errors'][0]['message'] = 'Возникло неожиданное исключение.';
            unset(
                $errorDetails['errors'][0]['line'],
                $errorDetails['errors'][0]['file'],
                $errorDetails['errors'][0]['trace']
            );
        }

        $response = new JsonResponse($errorDetails, Response::HTTP_BAD_REQUEST);

        $event->setResponse($response);
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (false === $event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $response = $event->getResponse();

        if (false === str_starts_with($request->getPathInfo(), '/api')) {
            return;
        }

        if (false === ($response instanceof JsonResponse)) {
            return;
        }

        $originalData = json_decode($response->getContent(), true);

        if (isset($originalData['status']) && 'error' === $originalData['status']) {
            return;
        }

        $successData = [
            'status' => 'success',
            'data' => $originalData,
        ];

        $response->setData($successData);
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
