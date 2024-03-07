<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307083313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE colonne ADD idtableau_id INT NOT NULL');
        $this->addSql('ALTER TABLE colonne ADD CONSTRAINT FK_65F87C447DCBC886 FOREIGN KEY (idtableau_id) REFERENCES tableau (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_65F87C447DCBC886 ON colonne (idtableau_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE colonne DROP CONSTRAINT FK_65F87C447DCBC886');
        $this->addSql('DROP INDEX IDX_65F87C447DCBC886');
        $this->addSql('ALTER TABLE colonne DROP idtableau_id');
    }
}
