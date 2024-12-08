<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use DateTimeImmutable;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\UuidV4;

final class Version20241205203424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates user roles';
    }

    public function up(Schema $schema): void
    {
        $currentDate = (new DateTimeImmutable())->format('Y-m-d H:i:s');
        $this->addSql(
            'INSERT INTO roles (id, slug, name, created_at, updated_at) VALUES (?, ?, ?, ?, ?)',
            [
                UuidV4::v4()->toString(),
                'ROLE_USER',
                'Пользователь',
                $currentDate,
                $currentDate,
            ]
        );
        $this->addSql(
            'INSERT INTO roles (id, slug, name, created_at, updated_at) VALUES (?, ?, ?, ?, ?)',
            [
                UuidV4::v4()->toString(),
                'ROLE_ADMIN',
                'Администратор',
                $currentDate,
                $currentDate,
            ]
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM roles WHERE slug IN (?, ?)', ['ROLE_USER', 'ROLE_ADMIN']);
    }
}
