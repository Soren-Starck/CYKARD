<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307082807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE colonne DROP CONSTRAINT colonne_pkey');
        $this->addSql('ALTER TABLE colonne RENAME COLUMN idcolonne TO id');
        $this->addSql('ALTER TABLE colonne ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE tableau DROP CONSTRAINT tableau_pkey');
        $this->addSql('ALTER TABLE tableau RENAME COLUMN idtableau TO id');
        $this->addSql('ALTER TABLE tableau ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX colonne_pkey');
        $this->addSql('ALTER TABLE colonne RENAME COLUMN id TO idcolonne');
        $this->addSql('ALTER TABLE colonne ADD PRIMARY KEY (idcolonne)');
        $this->addSql('DROP INDEX tableau_pkey');
        $this->addSql('ALTER TABLE tableau RENAME COLUMN id TO idtableau');
        $this->addSql('ALTER TABLE tableau ADD PRIMARY KEY (idtableau)');
    }
}
