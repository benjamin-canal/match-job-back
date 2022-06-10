<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220610090044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE technology_candidate (technology_id INT NOT NULL, candidate_id INT NOT NULL, INDEX IDX_7C9B732B4235D463 (technology_id), INDEX IDX_7C9B732B91BD8781 (candidate_id), PRIMARY KEY(technology_id, candidate_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technology_job (technology_id INT NOT NULL, job_id INT NOT NULL, INDEX IDX_44F58D5D4235D463 (technology_id), INDEX IDX_44F58D5DBE04EA9 (job_id), PRIMARY KEY(technology_id, job_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE technology_candidate ADD CONSTRAINT FK_7C9B732B4235D463 FOREIGN KEY (technology_id) REFERENCES technology (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE technology_candidate ADD CONSTRAINT FK_7C9B732B91BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE technology_job ADD CONSTRAINT FK_44F58D5D4235D463 FOREIGN KEY (technology_id) REFERENCES technology (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE technology_job ADD CONSTRAINT FK_44F58D5DBE04EA9 FOREIGN KEY (job_id) REFERENCES job (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE technology_candidate');
        $this->addSql('DROP TABLE technology_job');
    }
}
