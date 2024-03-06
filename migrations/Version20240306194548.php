<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306194548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE affectation (login VARCHAR(30) NOT NULL, idcarte INT NOT NULL, PRIMARY KEY(login, idcarte))');
        $this->addSql('CREATE UNIQUE INDEX affectation_pk ON affectation (login, idcarte)');
        $this->addSql('CREATE TABLE carte (idcarte INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, titrecarte VARCHAR(50) NOT NULL, descriptifcarte TEXT DEFAULT NULL, couleurcarte VARCHAR(7) NOT NULL, idcolonne INT NOT NULL, PRIMARY KEY(idcarte))');
        $this->addSql('CREATE TABLE colonne (idcolonne INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, titrecolonne VARCHAR(50) NOT NULL, PRIMARY KEY(idcolonne))');
        $this->addSql('CREATE TABLE tableau (idtableau INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, codetableau VARCHAR(255) NOT NULL, titretableau VARCHAR(50) NOT NULL, PRIMARY KEY(idtableau))');
        $this->addSql('CREATE TABLE tableauaffectation (idtableau INT NOT NULL, login VARCHAR(30) NOT NULL, PRIMARY KEY(idtableau, login))');
        $this->addSql('CREATE UNIQUE INDEX tableauaffectation_pk ON tableauaffectation (idtableau, login)');
        $this->addSql('CREATE TABLE tablecolonne (idtableau INT NOT NULL, idcolonne INT NOT NULL, PRIMARY KEY(idtableau, idcolonne))');
        $this->addSql('CREATE UNIQUE INDEX tablecolonne_pk ON tablecolonne (idtableau, idcolonne)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE affectation');
        $this->addSql('DROP TABLE carte');
        $this->addSql('DROP TABLE colonne');
        $this->addSql('DROP TABLE tableau');
        $this->addSql('DROP TABLE tableauaffectation');
        $this->addSql('DROP TABLE tablecolonne');
        $this->addSql('DROP INDEX user_pkey');
        $this->addSql('DROP INDEX uniq_identifier_login');
        $this->addSql('ALTER TABLE "user" ADD id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD PRIMARY KEY (id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_identifier_login ON "user" (login)');
    }
}
