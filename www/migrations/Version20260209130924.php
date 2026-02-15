<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260209130924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE charge (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(150) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE charge_people (charge_id INT NOT NULL, people_id INT NOT NULL, INDEX IDX_1DB5E6D055284914 (charge_id), INDEX IDX_1DB5E6D03147C936 (people_id), PRIMARY KEY (charge_id, people_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE eyes_color (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE gender (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE hair_color (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, people_id INT DEFAULT NULL, INDEX IDX_6A2CA10C3147C936 (people_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE nationality (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(150) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE nationality_people (nationality_id INT NOT NULL, people_id INT NOT NULL, INDEX IDX_EA2B88611C9DA55 (nationality_id), INDEX IDX_EA2B88613147C936 (people_id), PRIMARY KEY (nationality_id, people_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE people (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, lastname VARCHAR(150) NOT NULL, birth_date DATETIME NOT NULL, height INT DEFAULT NULL, weight INT DEFAULT NULL, is_captured TINYINT NOT NULL, features LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, birth_place VARCHAR(100) NOT NULL, research_by VARCHAR(100) NOT NULL, type VARCHAR(40) NOT NULL, hair_color_id INT DEFAULT NULL, gender_id INT DEFAULT NULL, eyes_color_id INT DEFAULT NULL, skin_color_id INT DEFAULT NULL, INDEX IDX_28166A268345DCB5 (hair_color_id), INDEX IDX_28166A26708A0E0 (gender_id), INDEX IDX_28166A262BA5DE30 (eyes_color_id), INDEX IDX_28166A2672E4CCB (skin_color_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE skin_color (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE spoken_langage (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(150) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE spoken_langage_people (spoken_langage_id INT NOT NULL, people_id INT NOT NULL, INDEX IDX_B427680DBBFCC71A (spoken_langage_id), INDEX IDX_B427680D3147C936 (people_id), PRIMARY KEY (spoken_langage_id, people_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(100) NOT NULL, lastname VARCHAR(150) NOT NULL, phone_number VARCHAR(10) NOT NULL, identity_number VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_active TINYINT NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE charge_people ADD CONSTRAINT FK_1DB5E6D055284914 FOREIGN KEY (charge_id) REFERENCES charge (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE charge_people ADD CONSTRAINT FK_1DB5E6D03147C936 FOREIGN KEY (people_id) REFERENCES people (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C3147C936 FOREIGN KEY (people_id) REFERENCES people (id)');
        $this->addSql('ALTER TABLE nationality_people ADD CONSTRAINT FK_EA2B88611C9DA55 FOREIGN KEY (nationality_id) REFERENCES nationality (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nationality_people ADD CONSTRAINT FK_EA2B88613147C936 FOREIGN KEY (people_id) REFERENCES people (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE people ADD CONSTRAINT FK_28166A268345DCB5 FOREIGN KEY (hair_color_id) REFERENCES hair_color (id)');
        $this->addSql('ALTER TABLE people ADD CONSTRAINT FK_28166A26708A0E0 FOREIGN KEY (gender_id) REFERENCES gender (id)');
        $this->addSql('ALTER TABLE people ADD CONSTRAINT FK_28166A262BA5DE30 FOREIGN KEY (eyes_color_id) REFERENCES eyes_color (id)');
        $this->addSql('ALTER TABLE people ADD CONSTRAINT FK_28166A2672E4CCB FOREIGN KEY (skin_color_id) REFERENCES skin_color (id)');
        $this->addSql('ALTER TABLE spoken_langage_people ADD CONSTRAINT FK_B427680DBBFCC71A FOREIGN KEY (spoken_langage_id) REFERENCES spoken_langage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE spoken_langage_people ADD CONSTRAINT FK_B427680D3147C936 FOREIGN KEY (people_id) REFERENCES people (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE charge_people DROP FOREIGN KEY FK_1DB5E6D055284914');
        $this->addSql('ALTER TABLE charge_people DROP FOREIGN KEY FK_1DB5E6D03147C936');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C3147C936');
        $this->addSql('ALTER TABLE nationality_people DROP FOREIGN KEY FK_EA2B88611C9DA55');
        $this->addSql('ALTER TABLE nationality_people DROP FOREIGN KEY FK_EA2B88613147C936');
        $this->addSql('ALTER TABLE people DROP FOREIGN KEY FK_28166A268345DCB5');
        $this->addSql('ALTER TABLE people DROP FOREIGN KEY FK_28166A26708A0E0');
        $this->addSql('ALTER TABLE people DROP FOREIGN KEY FK_28166A262BA5DE30');
        $this->addSql('ALTER TABLE people DROP FOREIGN KEY FK_28166A2672E4CCB');
        $this->addSql('ALTER TABLE spoken_langage_people DROP FOREIGN KEY FK_B427680DBBFCC71A');
        $this->addSql('ALTER TABLE spoken_langage_people DROP FOREIGN KEY FK_B427680D3147C936');
        $this->addSql('DROP TABLE charge');
        $this->addSql('DROP TABLE charge_people');
        $this->addSql('DROP TABLE eyes_color');
        $this->addSql('DROP TABLE gender');
        $this->addSql('DROP TABLE hair_color');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE nationality');
        $this->addSql('DROP TABLE nationality_people');
        $this->addSql('DROP TABLE people');
        $this->addSql('DROP TABLE skin_color');
        $this->addSql('DROP TABLE spoken_langage');
        $this->addSql('DROP TABLE spoken_langage_people');
        $this->addSql('DROP TABLE `user`');
    }
}
