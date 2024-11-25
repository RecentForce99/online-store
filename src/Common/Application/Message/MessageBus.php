<?php

declare(strict_types=1);

namespace App\Common\Application\Message;

use App\Common\Domain\Message\MessageBusInterface;
use Interop\Queue\ConnectionFactory;
use Interop\Queue\Exception;
use Interop\Queue\Exception\InvalidDestinationException;
use Interop\Queue\Exception\InvalidMessageException;
use JsonException;

final readonly class MessageBus implements MessageBusInterface
{
    public function __construct(
        private ConnectionFactory $connectionFactory,
    ) {
    }

    /**
     * @throws InvalidMessageException
     * @throws InvalidDestinationException
     * @throws Exception
     * @throws JsonException
     */
    public function publish(string $topic, array $message): void
    {
        $context = $this->connectionFactory->createContext();

        $serializedMessage = json_encode($message, JSON_THROW_ON_ERROR);

        $message = $context->createMessage($serializedMessage);
        $topic = $context->createTopic($topic);

        $context->createProducer()->send($topic, $message);
    }
}
