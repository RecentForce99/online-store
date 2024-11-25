<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Presentation\Console;

use App\Common\Infrastructure\Enum\Kafka\KafkaTopicEnum;
use App\Common\Infrastructure\Repository\Flusher;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use Exception;
use Interop\Queue\ConnectionFactory;
use Interop\Queue\Message;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ConsumeProductsFromKafkaCommand extends Command
{
    private const string NAME = 'app:kafka:consumer:products';
    private const string DESCRIPTION = 'Consume products from Kafka topic.';
    private const int DELAY_TO_GET_MESSAGES_IN_MS = 1000;
    private string $topic;

    public function __construct(
        private readonly ConnectionFactory $connectionFactory,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly Flusher $flusher,
    ) {
        parent::__construct();

        $this->topic = KafkaTopicEnum::PRODUCT_CHANGES->value;
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription(self::DESCRIPTION);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Starting Kafka consumer...');

        $context = $this->connectionFactory->createContext();
        $queue = $context->createQueue($this->topic);

        $consumer = $context->createConsumer($queue);
        $consumer->setCommitAsync(true);

        $output->writeln("Listening to topic: $this->topic");

        while (true) {
            $message = $consumer->receive(self::DELAY_TO_GET_MESSAGES_IN_MS);

            if (false === is_null($message)) {
                try {
                    $this->saveProduct($message);
                    $consumer->acknowledge($message);
                    $output->writeln('Acknowledged product changes');
                } catch (Exception $e) {
                    $consumer->reject($message);
                    $output->writeln("Rejected product changes: {$e->getMessage()}");
                }
            }
        }
    }

    private function saveProduct(Message $message): void
    {
        $productFields = json_decode($message->getBody(), true);
        $existingProduct = $this->productRepository->findById($productFields['id']);

        true === is_null($existingProduct)
            ? $this->createProduct($productFields)
            : $this->updateProduct($existingProduct, $productFields);

        $this->flusher->flush();
    }

    private function createProduct(array $productFields): void
    {
        $this->productRepository->create(
            Product::create(
                name: $productFields['name'],
                weight: $productFields['measurements']['weight'],
                height: $productFields['measurements']['height'],
                width: $productFields['measurements']['width'],
                length: $productFields['measurements']['length'],
                description: $productFields['description'],
                cost: $productFields['cost'],
                tax: $productFields['tax'],
                version: $productFields['version'],
            ),
        );
    }

    private function updateProduct(Product $existingProduct, array $productFields): void
    {
        $existingProduct
            ->setName($productFields['name'])
            ->setWeight($productFields['measurements']['weight'])
            ->setHeight($productFields['measurements']['height'])
            ->setWidth($productFields['measurements']['width'])
            ->setLength($productFields['measurements']['length'])
            ->setDescription($productFields['description'])
            ->setCost($productFields['cost'])
            ->setTax($productFields['tax'])
            ->setVersion($productFields['version']);
    }
}
