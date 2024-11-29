<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Product\Kafka;

use App\User\Domain\MessageBus\ProductChanges;
use App\User\Domain\MessageBus\ProductChangesMeasurements;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\UuidV4;

final class CreateProductChangesTopicToKafkaFixture extends Fixture
{
    public function __construct(
        private MessageBusInterface $messageBus,
    )
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function load(ObjectManager $manager): void
    {
        $products = $this->getProductsToSend();

        foreach ($products as $product) {
            $this->messageBus->dispatch($product);
        }
    }

    private function getProductsToSend(): array
    {
        return [
            new ProductChanges(
                id: UuidV4::v4()->toString(),
                name: 'Product1',
                measurements: new ProductChangesMeasurements(
                    weight: 100,
                    height: 100,
                    width: 100,
                    length: 100
                ),
                description: 'Description 1',
                cost: 1000,
                tax: 20,
                version: 1
            ),
            new ProductChanges(
                id: UuidV4::v4()->toString(),
                name: 'Product2',
                measurements: new ProductChangesMeasurements(
                    weight: 200,
                    height: 200,
                    width: 200,
                    length: 200
                ),
                description: 'Description 2',
                cost: 2000,
                tax: 40,
                version: 1
            ),
            new ProductChanges(
                id: UuidV4::v4()->toString(),
                name: 'Product3',
                measurements: new ProductChangesMeasurements(
                    weight: 300,
                    height: 300,
                    width: 300,
                    length: 300
                ),
                description: 'Description 3',
                cost: 3000,
                tax: 60,
                version: 1
            ),
        ];
    }
}
