<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220617100404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adress (id INT AUTO_INCREMENT NOT NULL, street_number SMALLINT DEFAULT NULL, street_name VARCHAR(128) NOT NULL, zip VARCHAR(5) NOT NULL, city VARCHAR(128) NOT NULL, department VARCHAR(128) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidate (id INT AUTO_INCREMENT NOT NULL, adress_id INT NOT NULL, user_id INT NOT NULL, contract_id INT NOT NULL, experience_id INT NOT NULL, jobtitle_id INT NOT NULL, salary_id INT NOT NULL, last_name VARCHAR(64) NOT NULL, first_name VARCHAR(64) DEFAULT NULL, birthday DATE DEFAULT NULL, genre SMALLINT NOT NULL, phone_number VARCHAR(25) DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, resume VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, position_held VARCHAR(128) DEFAULT NULL, portfolio VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_C8B28E448486F9AC (adress_id), INDEX IDX_C8B28E44A76ED395 (user_id), INDEX IDX_C8B28E442576E0FD (contract_id), INDEX IDX_C8B28E4446E90E27 (experience_id), INDEX IDX_C8B28E44E438D15B (jobtitle_id), INDEX IDX_C8B28E44B0FDF16E (salary_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, sector_id INT NOT NULL, adress_id INT NOT NULL, company_name VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_4FBF094FDE95C867 (sector_id), INDEX IDX_4FBF094F8486F9AC (adress_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contract (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, years_number VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, recruiter_id INT NOT NULL, contract_id INT NOT NULL, experience_id INT NOT NULL, jobtitle_id INT NOT NULL, salary_id INT NOT NULL, job_name VARCHAR(128) NOT NULL, description LONGTEXT NOT NULL, status SMALLINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_FBD8E0F8156BE243 (recruiter_id), INDEX IDX_FBD8E0F82576E0FD (contract_id), INDEX IDX_FBD8E0F846E90E27 (experience_id), INDEX IDX_FBD8E0F8E438D15B (jobtitle_id), INDEX IDX_FBD8E0F8B0FDF16E (salary_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jobtitle (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matchup (id INT AUTO_INCREMENT NOT NULL, candidate_id INT DEFAULT NULL, job_id INT DEFAULT NULL, candidate_status TINYINT(1) DEFAULT 0 NOT NULL, recruiter_status TINYINT(1) DEFAULT 0 NOT NULL, match_status TINYINT(1) DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_D5ED565191BD8781 (candidate_id), INDEX IDX_D5ED5651BE04EA9 (job_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recruiter (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, user_id INT NOT NULL, lastname VARCHAR(64) NOT NULL, firstname VARCHAR(64) DEFAULT NULL, phone_number VARCHAR(25) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_DE8633D8979B1AD6 (company_id), INDEX IDX_DE8633D8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salary (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sector (id INT AUTO_INCREMENT NOT NULL, sector_name VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technology (id INT AUTO_INCREMENT NOT NULL, technology_name VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technology_candidate (technology_id INT NOT NULL, candidate_id INT NOT NULL, INDEX IDX_7C9B732B4235D463 (technology_id), INDEX IDX_7C9B732B91BD8781 (candidate_id), PRIMARY KEY(technology_id, candidate_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technology_job (technology_id INT NOT NULL, job_id INT NOT NULL, INDEX IDX_44F58D5D4235D463 (technology_id), INDEX IDX_44F58D5DBE04EA9 (job_id), PRIMARY KEY(technology_id, job_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E448486F9AC FOREIGN KEY (adress_id) REFERENCES adress (id)');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E44A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E442576E0FD FOREIGN KEY (contract_id) REFERENCES contract (id)');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E4446E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id)');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E44E438D15B FOREIGN KEY (jobtitle_id) REFERENCES jobtitle (id)');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E44B0FDF16E FOREIGN KEY (salary_id) REFERENCES salary (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FDE95C867 FOREIGN KEY (sector_id) REFERENCES sector (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F8486F9AC FOREIGN KEY (adress_id) REFERENCES adress (id)');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F8156BE243 FOREIGN KEY (recruiter_id) REFERENCES recruiter (id)');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F82576E0FD FOREIGN KEY (contract_id) REFERENCES contract (id)');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F846E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id)');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F8E438D15B FOREIGN KEY (jobtitle_id) REFERENCES jobtitle (id)');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F8B0FDF16E FOREIGN KEY (salary_id) REFERENCES salary (id)');
        $this->addSql('ALTER TABLE matchup ADD CONSTRAINT FK_D5ED565191BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id)');
        $this->addSql('ALTER TABLE matchup ADD CONSTRAINT FK_D5ED5651BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE recruiter ADD CONSTRAINT FK_DE8633D8979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE recruiter ADD CONSTRAINT FK_DE8633D8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE technology_candidate ADD CONSTRAINT FK_7C9B732B4235D463 FOREIGN KEY (technology_id) REFERENCES technology (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE technology_candidate ADD CONSTRAINT FK_7C9B732B91BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE technology_job ADD CONSTRAINT FK_44F58D5D4235D463 FOREIGN KEY (technology_id) REFERENCES technology (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE technology_job ADD CONSTRAINT FK_44F58D5DBE04EA9 FOREIGN KEY (job_id) REFERENCES job (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E448486F9AC');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F8486F9AC');
        $this->addSql('ALTER TABLE matchup DROP FOREIGN KEY FK_D5ED565191BD8781');
        $this->addSql('ALTER TABLE technology_candidate DROP FOREIGN KEY FK_7C9B732B91BD8781');
        $this->addSql('ALTER TABLE recruiter DROP FOREIGN KEY FK_DE8633D8979B1AD6');
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E442576E0FD');
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F82576E0FD');
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E4446E90E27');
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F846E90E27');
        $this->addSql('ALTER TABLE matchup DROP FOREIGN KEY FK_D5ED5651BE04EA9');
        $this->addSql('ALTER TABLE technology_job DROP FOREIGN KEY FK_44F58D5DBE04EA9');
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E44E438D15B');
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F8E438D15B');
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F8156BE243');
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E44B0FDF16E');
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F8B0FDF16E');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FDE95C867');
        $this->addSql('ALTER TABLE technology_candidate DROP FOREIGN KEY FK_7C9B732B4235D463');
        $this->addSql('ALTER TABLE technology_job DROP FOREIGN KEY FK_44F58D5D4235D463');
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E44A76ED395');
        $this->addSql('ALTER TABLE recruiter DROP FOREIGN KEY FK_DE8633D8A76ED395');
        $this->addSql('DROP TABLE adress');
        $this->addSql('DROP TABLE candidate');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE contract');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE jobtitle');
        $this->addSql('DROP TABLE matchup');
        $this->addSql('DROP TABLE recruiter');
        $this->addSql('DROP TABLE salary');
        $this->addSql('DROP TABLE sector');
        $this->addSql('DROP TABLE technology');
        $this->addSql('DROP TABLE technology_candidate');
        $this->addSql('DROP TABLE technology_job');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
