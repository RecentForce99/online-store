<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Console\MessageBus;

use App\Product\Domain\MessageBus\ProductChanges;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class KafkaProductConsumer
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(ProductChanges $message): void
    {
        $this->messageBus->dispatch($message);
    }
}
