<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250325160928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE content CHANGE cover_id cover_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE upload DROP updated_at, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE filename filename VARCHAR(255) NOT NULL, CHANGE mime_type mime_type VARCHAR(255) NOT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE upload ADD updated_at DATETIME NOT NULL, CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE filename filename VARCHAR(255) DEFAULT NULL, CHANGE mime_type mime_type VARCHAR(255) DEFAULT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE content CHANGE cover_id cover_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
    }
}
