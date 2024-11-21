<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241120104316 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment CHANGE updated_at updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE content CHANGE updated_at updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE upload CHANGE updated_at updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE upload CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE comment CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE content CHANGE updated_at updated_at DATETIME DEFAULT NULL');
    }
}
