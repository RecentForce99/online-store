<?php

declare(strict_types=1);

namespace App\Report\Infrastructure\Controller;

use App\Report\Application\Exception\NotFoundProductsSoldInLast24HoursException;
use App\Report\Application\UseCase\SoldProduct\SoldProductReportCommand;
use App\Report\Domain\MessageBus\SoldProducts\SoldProductsErrorDetails;
use App\Report\Domain\MessageBus\SoldProducts\SoldProductsReport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[Route('/report')]
final class ReportController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @throws ExceptionInterface
     * @throws Throwable
     */
    #[Route(methods: ['POST'], path: '/generate/soldProducts', name: 'report.sold_products.generate')]
    public function generateSoldProductsReport(
        SoldProductReportCommand $soldProductReportCommand,
    ): JsonResponse {
        $responseCode = Response::HTTP_CREATED;
        $errorText = 'Ошибка формировании ежедневого отчёта.';

        try {
            $this->messageBus->dispatch($soldProductReportCommand);
        } catch (HandlerFailedException $e) {
            $previousException = $e->getPrevious();
            switch (get_class($previousException)) {
                case NotFoundProductsSoldInLast24HoursException::class:
                    $errorDetail = new SoldProductsErrorDetails(
                        error: $errorText,
                        message: $previousException->getMessage(),
                    );
                    $responseCode = Response::HTTP_NOT_FOUND;
                    break;
                default:
                    $errorDetail = new SoldProductsErrorDetails(
                        error: $errorText,
                        message: 'Неожиданное исключение.',
                    );
                    $responseCode = Response::HTTP_UNPROCESSABLE_ENTITY;
                    break;
            }
        }

        $result = new SoldProductsReport(
            reportId: $soldProductReportCommand->reportId,
            result: isset($errorDetail) ? 'fail' : 'success',
            detail: $errorDetail ?? null,
        );
        $this->messageBus->dispatch($result);

        return new JsonResponse(
            data: $result,
            status: $responseCode,
        );
    }
}
