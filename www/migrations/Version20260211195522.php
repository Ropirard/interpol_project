<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260211195522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_published TINYINT NOT NULL, is_active TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE article_people (article_id INT NOT NULL, people_id INT NOT NULL, INDEX IDX_42D1FDA67294869C (article_id), INDEX IDX_42D1FDA63147C936 (people_id), PRIMARY KEY (article_id, people_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE article_people ADD CONSTRAINT FK_42D1FDA67294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_people ADD CONSTRAINT FK_42D1FDA63147C936 FOREIGN KEY (people_id) REFERENCES people (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media ADD article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10C7294869C ON media (article_id)');
        $this->addSql('ALTER TABLE people DROP FOREIGN KEY `FK_28166A262BA5DE30`');
        $this->addSql('ALTER TABLE people DROP FOREIGN KEY `FK_28166A26708A0E0`');
        $this->addSql('ALTER TABLE people DROP FOREIGN KEY `FK_28166A2672E4CCB`');
        $this->addSql('ALTER TABLE people DROP FOREIGN KEY `FK_28166A268345DCB5`');
        $this->addSql('ALTER TABLE people CHANGE is_active is_active TINYINT NOT NULL');
        $this->addSql('ALTER TABLE people ADD CONSTRAINT FK_28166A262BA5DE30 FOREIGN KEY (eyes_color_id) REFERENCES eyes_color (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE people ADD CONSTRAINT FK_28166A26708A0E0 FOREIGN KEY (gender_id) REFERENCES gender (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE people ADD CONSTRAINT FK_28166A2672E4CCB FOREIGN KEY (skin_color_id) REFERENCES skin_color (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE people ADD CONSTRAINT FK_28166A268345DCB5 FOREIGN KEY (hair_color_id) REFERENCES hair_color (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_people DROP FOREIGN KEY FK_42D1FDA67294869C');
        $this->addSql('ALTER TABLE article_people DROP FOREIGN KEY FK_42D1FDA63147C936');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_people');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C7294869C');
        $this->addSql('DROP INDEX IDX_6A2CA10C7294869C ON media');
        $this->addSql('ALTER TABLE media DROP article_id');
        $this->addSql('ALTER TABLE people DROP FOREIGN KEY FK_28166A268345DCB5');
        $this->addSql('ALTER TABLE people DROP FOREIGN KEY FK_28166A26708A0E0');
        $this->addSql('ALTER TABLE people DROP FOREIGN KEY FK_28166A262BA5DE30');
        $this->addSql('ALTER TABLE people DROP FOREIGN KEY FK_28166A2672E4CCB');
        $this->addSql('ALTER TABLE people CHANGE is_active is_active TINYINT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE people ADD CONSTRAINT `FK_28166A268345DCB5` FOREIGN KEY (hair_color_id) REFERENCES hair_color (id)');
        $this->addSql('ALTER TABLE people ADD CONSTRAINT `FK_28166A26708A0E0` FOREIGN KEY (gender_id) REFERENCES gender (id)');
        $this->addSql('ALTER TABLE people ADD CONSTRAINT `FK_28166A262BA5DE30` FOREIGN KEY (eyes_color_id) REFERENCES eyes_color (id)');
        $this->addSql('ALTER TABLE people ADD CONSTRAINT `FK_28166A2672E4CCB` FOREIGN KEY (skin_color_id) REFERENCES skin_color (id)');
    }
}
