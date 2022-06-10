<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220610091323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidate ADD contract_id INT NOT NULL');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E442576E0FD FOREIGN KEY (contract_id) REFERENCES contract (id)');
        $this->addSql('CREATE INDEX IDX_C8B28E442576E0FD ON candidate (contract_id)');
        $this->addSql('ALTER TABLE job ADD contract_id INT NOT NULL');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F82576E0FD FOREIGN KEY (contract_id) REFERENCES contract (id)');
        $this->addSql('CREATE INDEX IDX_FBD8E0F82576E0FD ON job (contract_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E442576E0FD');
        $this->addSql('DROP INDEX IDX_C8B28E442576E0FD ON candidate');
        $this->addSql('ALTER TABLE candidate DROP contract_id');
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F82576E0FD');
        $this->addSql('DROP INDEX IDX_FBD8E0F82576E0FD ON job');
        $this->addSql('ALTER TABLE job DROP contract_id');
    }
}
