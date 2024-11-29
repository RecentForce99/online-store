<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Product\Kafka;

use App\Product\Domain\MessageBus\ProductChanges;
use App\Product\Domain\MessageBus\ProductChangesMeasurements;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

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
        $uuid1 = 'ed63267d-e7e2-4a24-8bfd-c74afc65f041';
        $uuid2 = 'ed63267d-e7e2-4a24-8bfd-c74afc65f042';
        $uuid3 = 'ed63267d-e7e2-4a24-8bfd-c74afc65f043';

        return [
            new ProductChanges(
                id: $uuid1,
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
                id: $uuid2,
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
                id: $uuid3,
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
