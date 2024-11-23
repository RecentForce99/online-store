<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Fixture\Kafka;

use App\Common\Infrastructure\Enum\Kafka\KafkaTopicEnum;
use App\Product\Infrastructure\Dto\Fixture\Kafka\ProductForSendingToKafkaDto;
use App\Product\Infrastructure\Dto\Fixture\Kafka\ProductMeasurementsForSendingToKafkaDto;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Interop\Queue\ConnectionFactory;
use Interop\Queue\Context;
use Interop\Queue\Exception;
use Interop\Queue\Exception\InvalidDestinationException;
use Interop\Queue\Exception\InvalidMessageException;
use Interop\Queue\Message;
use Interop\Queue\Producer;
use Interop\Queue\Topic;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Uid\UuidV4;

final class CreateProductChangesTopicToKafkaFixture extends Fixture
{
    private const string SERIALIZE_FORMAT = 'json';

    private string $topic;
    private Serializer $serializer;

    public function __construct(
        private readonly ConnectionFactory $connectionFactory,
    )
    {
        $this->topic = KafkaTopicEnum::PRODUCT_CHANGES->value;
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
    }

    public function load(ObjectManager $manager)
    {
        $context = $this->connectionFactory->createContext();
        $topic = $context->createTopic($this->topic);

        $productsToSend = $this->getProductsToSend();
        $messagesToSend = $this->getMessagesToSend($context, $productsToSend);

        $producer = $context->createProducer();

        $this->sendMessages($producer, $topic, $messagesToSend);
    }

    private function getProductsToSend(): array
    {
        return [
            new ProductForSendingToKafkaDto(
                id: UuidV4::v4()->toString(),
                name: 'Product1',
                measurements: new ProductMeasurementsForSendingToKafkaDto(
                    weight: 100,
                    height: 100,
                    width: 100,
                    length: 100,
                ),
                description: 'Description 1',
                cost: 1000,
                tax: 20,
                version: 1,
            ),
            new ProductForSendingToKafkaDto(
                id: UuidV4::v4()->toString(),
                name: 'Product2',
                measurements: new ProductMeasurementsForSendingToKafkaDto(
                    weight: 200,
                    height: 200,
                    width: 200,
                    length: 200,
                ),
                description: 'Description 2',
                cost: 2000,
                tax: 40,
                version: 1,
            ),
            new ProductForSendingToKafkaDto(
                id: UuidV4::v4()->toString(),
                name: 'Product3',
                measurements: new ProductMeasurementsForSendingToKafkaDto(
                    weight: 300,
                    height: 300,
                    width: 300,
                    length: 300,
                ),
                description: 'Description 3',
                cost: 3000,
                tax: 60,
                version: 1,
            ),
        ];
    }

    private function getMessagesToSend(Context $context, array $productsToSend): array
    {
        $messagesToSend = [];

        /* @var ProductForSendingToKafkaDto $product */
        foreach ($productsToSend as $product) {
            $body = $this->serializer->serialize($product, self::SERIALIZE_FORMAT);
            $messagesToSend[] = $context->createMessage($body);
        }

        return $messagesToSend;
    }

    /**
     * @throws InvalidDestinationException
     * @throws InvalidMessageException
     * @throws Exception
     */
    private function sendMessages(Producer $producer, Topic $topic, array $messagesToSend): void
    {
        /* @var Message $message */
        foreach ($messagesToSend as $message) {
            $producer->send($topic, $message);
        }
    }
}
