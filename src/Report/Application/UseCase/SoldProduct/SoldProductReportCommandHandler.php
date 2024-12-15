<?php

declare(strict_types=1);

namespace App\Report\Application\UseCase\SoldProduct;

use App\Common\Application\Filesystem\FilesystemInterface;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Report\Application\Dto\SoldProductReportDto;
use App\Report\Application\Dto\SoldProductReportUserDto;
use App\Report\Application\Exception\NotFoundProductsSoldInLast24HoursException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\UuidV4;

#[AsMessageHandler]
final readonly class SoldProductReportCommandHandler
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private SerializerInterface $serializer,
        private FilesystemInterface $filesystem,
        private string $reportsDirectory,
    ) {
    }

    /**
     * @throws NotFoundProductsSoldInLast24HoursException
     */
    public function __invoke(SoldProductReportCommand $soldProductReportCommand): void
    {
        $reportId = $soldProductReportCommand->reportId;

        $this->createDirectoryIfDoesntExist();

        $productsSoldInLast24Hours = $this->productRepository->getProductsSoldInLast24Hours();
        if (empty($productsSoldInLast24Hours)) {
            throw NotFoundProductsSoldInLast24HoursException::byReportId($reportId);
        }

        $filePath = "$this->reportsDirectory/$reportId";
        foreach ($productsSoldInLast24Hours as $product) {
            /* @var UuidV4 $userUuid */
            $userUuid = $product['user_id'];

            $productDto = new SoldProductReportDto(
                product_name: $product['product_name'],
                price: $product['price'],
                amount: $product['amount'],
                user: new SoldProductReportUserDto(
                    id: $userUuid->toString(),
                ),
            );

            $productJson = $this->serializer->serialize($productDto, 'json');

            $this->filesystem->appendToFile($filePath, $productJson);
        }
    }

    private function createDirectoryIfDoesntExist(): void
    {
        if (false === $this->filesystem->exists($this->reportsDirectory)) {
            $this->filesystem->mkdir($this->reportsDirectory, 0755);
        }
    }
}
