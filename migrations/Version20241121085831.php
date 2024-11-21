<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241121085831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment CHANGE updated_at updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE content ADD cover_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', DROP cover, CHANGE updated_at updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9922726E9 FOREIGN KEY (cover_id) REFERENCES upload (id)');
        $this->addSql('CREATE INDEX IDX_FEC530A9922726E9 ON content (cover_id)');
        $this->addSql('ALTER TABLE upload CHANGE updated_at updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE upload CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE comment CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9922726E9');
        $this->addSql('DROP INDEX IDX_FEC530A9922726E9 ON content');
        $this->addSql('ALTER TABLE content ADD cover VARCHAR(255) DEFAULT NULL, DROP cover_id, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
    }
}
