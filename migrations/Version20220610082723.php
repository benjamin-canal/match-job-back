<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220610082723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matchup ADD candidate_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE matchup ADD CONSTRAINT FK_D5ED565191BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id)');
        $this->addSql('CREATE INDEX IDX_D5ED565191BD8781 ON matchup (candidate_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matchup DROP FOREIGN KEY FK_D5ED565191BD8781');
        $this->addSql('DROP INDEX IDX_D5ED565191BD8781 ON matchup');
        $this->addSql('ALTER TABLE matchup DROP candidate_id');
    }
}
