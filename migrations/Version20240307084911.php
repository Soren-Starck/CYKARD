<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307084911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carte DROP CONSTRAINT carte_pkey');
        $this->addSql('ALTER TABLE carte ADD colonne_id INT NOT NULL');
        $this->addSql('ALTER TABLE carte RENAME COLUMN idcarte TO id');
        $this->addSql('ALTER TABLE carte ADD CONSTRAINT FK_BAD4FFFD213EAC9D FOREIGN KEY (colonne_id) REFERENCES colonne (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_BAD4FFFD213EAC9D ON carte (colonne_id)');
        $this->addSql('ALTER TABLE carte ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
       $this->addSql('ALTER TABLE carte DROP CONSTRAINT FK_BAD4FFFD213EAC9D');
        $this->addSql('DROP INDEX IDX_BAD4FFFD213EAC9D');
        $this->addSql('DROP INDEX carte_pkey');
        $this->addSql('ALTER TABLE carte DROP colonne_id');
        $this->addSql('ALTER TABLE carte RENAME COLUMN id TO idcarte');
        $this->addSql('ALTER TABLE carte ADD PRIMARY KEY (idcarte)');
    }
}
