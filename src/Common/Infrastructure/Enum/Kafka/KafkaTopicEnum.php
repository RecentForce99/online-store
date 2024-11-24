<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Enum\Kafka;

enum KafkaTopicEnum: string
{
    case NEW_USER = 'new_user';
    case REPORT = 'report';
    case PRODUCT_CHANGES = 'product_changes';
    case ORDER_CHANGES = 'order_changes';
}
