<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307083940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE colonne DROP CONSTRAINT fk_65f87c447dcbc886');
        $this->addSql('DROP INDEX idx_65f87c447dcbc886');
        $this->addSql('ALTER TABLE colonne RENAME COLUMN idtableau_id TO tableau_id');
        $this->addSql('ALTER TABLE colonne ADD CONSTRAINT FK_65F87C44B062D5BC FOREIGN KEY (tableau_id) REFERENCES tableau (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_65F87C44B062D5BC ON colonne (tableau_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE colonne DROP CONSTRAINT FK_65F87C44B062D5BC');
        $this->addSql('DROP INDEX IDX_65F87C44B062D5BC');
        $this->addSql('ALTER TABLE colonne RENAME COLUMN tableau_id TO idtableau_id');
        $this->addSql('ALTER TABLE colonne ADD CONSTRAINT fk_65f87c447dcbc886 FOREIGN KEY (idtableau_id) REFERENCES tableau (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_65f87c447dcbc886 ON colonne (idtableau_id)');
    }
}
