<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220610092014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidate ADD salary_id INT NOT NULL');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E44B0FDF16E FOREIGN KEY (salary_id) REFERENCES salary (id)');
        $this->addSql('CREATE INDEX IDX_C8B28E44B0FDF16E ON candidate (salary_id)');
        $this->addSql('ALTER TABLE job ADD salary_id INT NOT NULL');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F8B0FDF16E FOREIGN KEY (salary_id) REFERENCES salary (id)');
        $this->addSql('CREATE INDEX IDX_FBD8E0F8B0FDF16E ON job (salary_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E44B0FDF16E');
        $this->addSql('DROP INDEX IDX_C8B28E44B0FDF16E ON candidate');
        $this->addSql('ALTER TABLE candidate DROP salary_id');
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F8B0FDF16E');
        $this->addSql('DROP INDEX IDX_FBD8E0F8B0FDF16E ON job');
        $this->addSql('ALTER TABLE job DROP salary_id');
    }
}
