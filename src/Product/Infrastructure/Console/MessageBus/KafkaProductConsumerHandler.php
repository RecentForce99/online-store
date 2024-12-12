<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Console\MessageBus;

use App\Common\Infrastructure\Repository\Flusher;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\MessageBus\ProductChanges;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use DateTimeImmutable;
use DateTimeZone;
use Exception;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\UuidV4;

#[AsMessageHandler]
class KafkaProductConsumerHandler
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private Flusher $flusher,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(ProductChanges $message): void
    {
        $existingProduct = $this->productRepository->findById($message->id);
        null === $existingProduct
            ? $this->createProduct($message)
            : $this->updateProduct($existingProduct, $message);

        $this->flusher->flush();
    }

    private function createProduct(ProductChanges $message): void
    {
        $product = Product::create(
            name: $message->name,
            weight: $message->measurements->weight,
            height: $message->measurements->height,
            width: $message->measurements->width,
            length: $message->measurements->length,
            description: $message->description,
            cost: $message->cost,
            tax: $message->tax,
            version: $message->version,
            id: UuidV4::fromString($message->id),
        );

        $this->productRepository->add($product);
    }

    /**
     * @throws Exception
     */
    private function updateProduct(Product $existingProduct, ProductChanges $message): void
    {
        $existingProduct
            ->setName($message->name)
            ->setWeight($message->measurements->weight)
            ->setHeight($message->measurements->height)
            ->setWidth($message->measurements->width)
            ->setLength($message->measurements->length)
            ->setDescription($message->description)
            ->setCost($message->cost)
            ->setTax($message->tax)
            ->setVersion($message->version)
            ->setUpdatedAt(new DateTimeImmutable('now', new DateTimeZone('Europe/Moscow')));
    }
}
