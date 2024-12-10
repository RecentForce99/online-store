<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use DateTimeImmutable;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241205210447 extends AbstractMigration
{
    private const array ORDER_STATUSES = [
        [
            'payment_required',
            'Ожидается оплата',
            0,
        ],
        [
            'payment_successful',
            'Ожидается Оплачен',
            1,
        ],
        [
            'assembly_awaited',
            'Ждёт сборки',
            0,
        ],
        [
            'assembling',
            'В сборке',
            0,
        ],
        [
            'delivering',
            'Доставляется',
            0,
        ],
        [
            'ready',
            'Готов к выдаче',
            1,
        ],
        [
            'received',
            'Получен',
            1,
        ],
        [
            'canceled',
            'Отменён',
            1,
        ],
    ];

    public function getDescription(): string
    {
        return 'Creates order statuses';
    }

    public function up(Schema $schema): void
    {
        $currentDate = (new DateTimeImmutable())->format('Y-m-d H:i:s');
        foreach (self::ORDER_STATUSES as [$slug, $name, $notifiable]) {
            $this->addSql(
                'INSERT INTO order_statuses (slug, name, notifiable, created_at, updated_at) VALUES (?, ?, ?, ?, ?)',
                [
                    $slug,
                    $name,
                    $notifiable,
                    $currentDate,
                    $currentDate,
                ]
            );
        }
    }

    public function down(Schema $schema): void
    {
        $slugsToDelete = array_map(fn(array $orderStatus) => current($orderStatus), self::ORDER_STATUSES);
        $this->addSql('DELETE FROM order_statuses WHERE slug IN (?)', [implode(', ', $slugsToDelete)]);
    }
}
