<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Adds missing is_active column on people table.
 */
final class Version20260211120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add is_active column to people table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE people ADD is_active TINYINT(1) NOT NULL DEFAULT 1');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE people DROP is_active');
    }
}
