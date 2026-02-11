<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260210102908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, resolved_at DATETIME DEFAULT NULL, statut VARCHAR(50) NOT NULL, user_id INT DEFAULT NULL, people_id INT DEFAULT NULL, type_report_id INT DEFAULT NULL, INDEX IDX_C42F7784A76ED395 (user_id), INDEX IDX_C42F77843147C936 (people_id), INDEX IDX_C42F7784FF14E5D0 (type_report_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE type_report (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F77843147C936 FOREIGN KEY (people_id) REFERENCES people (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784FF14E5D0 FOREIGN KEY (type_report_id) REFERENCES type_report (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784A76ED395');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F77843147C936');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784FF14E5D0');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE type_report');
    }
}
