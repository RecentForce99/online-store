<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use DateTimeImmutable;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\UuidV4;

final class Version20241205205521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates order delivery types';
    }

    public function up(Schema $schema): void
    {
        $currentDate = (new DateTimeImmutable())->format('Y-m-d H:i:s');
        $this->addSql(
            'INSERT INTO delivery_types (slug, name, created_at, updated_at) VALUES (?, ?, ?, ?)',
            [
                'pickup',
                'Самовывоз',
                $currentDate,
                $currentDate,
            ]
        );
        $this->addSql(
            'INSERT INTO delivery_types (slug, name, created_at, updated_at) VALUES (?, ?, ?, ?)',
            [
                'courier',
                'Курьер',
                $currentDate,
                $currentDate,
            ]
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM delivery_types WHERE slug IN (?, ?)', ['pickup', 'courier']);
    }
}
