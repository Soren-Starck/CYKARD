<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307090518 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_carte (user_id INT NOT NULL, carte_id INT NOT NULL, PRIMARY KEY(user_id, carte_id))');
        $this->addSql('CREATE INDEX IDX_581604A5A76ED395 ON user_carte (user_id)');
        $this->addSql('CREATE INDEX IDX_581604A5C9C7CEB6 ON user_carte (carte_id)');
        $this->addSql('CREATE TABLE user_tableau (user_id INT NOT NULL, tableau_id INT NOT NULL, PRIMARY KEY(user_id, tableau_id))');
        $this->addSql('CREATE INDEX IDX_9E7953BBA76ED395 ON user_tableau (user_id)');
        $this->addSql('CREATE INDEX IDX_9E7953BBB062D5BC ON user_tableau (tableau_id)');
        $this->addSql('ALTER TABLE user_carte ADD CONSTRAINT FK_581604A5A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_carte ADD CONSTRAINT FK_581604A5C9C7CEB6 FOREIGN KEY (carte_id) REFERENCES carte (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_tableau ADD CONSTRAINT FK_9E7953BBA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_tableau ADD CONSTRAINT FK_9E7953BBB062D5BC FOREIGN KEY (tableau_id) REFERENCES tableau (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
       $this->addSql('ALTER TABLE user_carte DROP CONSTRAINT FK_581604A5A76ED395');
        $this->addSql('ALTER TABLE user_carte DROP CONSTRAINT FK_581604A5C9C7CEB6');
        $this->addSql('ALTER TABLE user_tableau DROP CONSTRAINT FK_9E7953BBA76ED395');
        $this->addSql('ALTER TABLE user_tableau DROP CONSTRAINT FK_9E7953BBB062D5BC');
        $this->addSql('DROP TABLE user_carte');
        $this->addSql('DROP TABLE user_tableau');
    }
}
