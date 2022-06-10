<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220610091558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidate ADD experience_id INT NOT NULL');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E4446E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id)');
        $this->addSql('CREATE INDEX IDX_C8B28E4446E90E27 ON candidate (experience_id)');
        $this->addSql('ALTER TABLE job ADD experience_id INT NOT NULL');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F846E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id)');
        $this->addSql('CREATE INDEX IDX_FBD8E0F846E90E27 ON job (experience_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E4446E90E27');
        $this->addSql('DROP INDEX IDX_C8B28E4446E90E27 ON candidate');
        $this->addSql('ALTER TABLE candidate DROP experience_id');
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F846E90E27');
        $this->addSql('DROP INDEX IDX_FBD8E0F846E90E27 ON job');
        $this->addSql('ALTER TABLE job DROP experience_id');
    }
}
