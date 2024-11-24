<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241122155117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE roles (slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(slug))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B63E2EC75E237E06 ON roles (name)');
        $this->addSql('COMMENT ON COLUMN roles.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN roles.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE users ADD role_slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE users ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E92622E9F6 FOREIGN KEY (role_slug) REFERENCES roles (slug) ON DELETE RESTRICT NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1483A5E92622E9F6 ON users (role_slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E92622E9F6');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP INDEX IDX_1483A5E92622E9F6');
        $this->addSql('ALTER TABLE users DROP role_slug');
        $this->addSql('ALTER TABLE users ALTER name TYPE VARCHAR(100)');
    }
}
