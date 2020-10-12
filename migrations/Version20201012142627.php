<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201012142627 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE campus (id_campus INT AUTO_INCREMENT NOT NULL, nom VARCHAR(30) NOT NULL, PRIMARY KEY(id_campus)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat (id_etat INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(30) NOT NULL, PRIMARY KEY(id_etat)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lieu (id_lieu INT AUTO_INCREMENT NOT NULL, id_ville INT NOT NULL, nom VARCHAR(30) NOT NULL, rue VARCHAR(30) DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, INDEX IDX_2F577D59AD4698F3 (id_ville), PRIMARY KEY(id_lieu)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participant (id_participant INT AUTO_INCREMENT NOT NULL, id_campus INT NOT NULL, nom VARCHAR(30) NOT NULL, prenom VARCHAR(30) NOT NULL, telephone VARCHAR(15) DEFAULT NULL, mail VARCHAR(20) NOT NULL, mot_passe VARCHAR(255) NOT NULL, administrateur TINYINT(1) NOT NULL, actif TINYINT(1) NOT NULL, INDEX IDX_D79F6B11BB3EDDFC (id_campus), PRIMARY KEY(id_participant)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participant_sortie (id_participant INT NOT NULL, id_sortie INT NOT NULL, INDEX IDX_8E436D73CF8DA6E6 (id_participant), INDEX IDX_8E436D731A08661F (id_sortie), PRIMARY KEY(id_participant, id_sortie)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sortie (id_sortie INT AUTO_INCREMENT NOT NULL, id_participant INT NOT NULL, id_lieu INT NOT NULL, id_etat INT NOT NULL, id_campus INT NOT NULL, nom VARCHAR(30) NOT NULL, date_heure_debut DATETIME NOT NULL, duree INT DEFAULT NULL, date_limite_inscription DATETIME NOT NULL, nb_inscriptions_max INT NOT NULL, infos_sortie VARCHAR(500) DEFAULT NULL, INDEX IDX_3C3FD3F2CF8DA6E6 (id_participant), INDEX IDX_3C3FD3F2A477615B (id_lieu), INDEX IDX_3C3FD3F2DEEAEB60 (id_etat), INDEX IDX_3C3FD3F2BB3EDDFC (id_campus), PRIMARY KEY(id_sortie)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sortie_participant (id_sortie INT NOT NULL, id_participant INT NOT NULL, INDEX IDX_E6D4CDAD1A08661F (id_sortie), INDEX IDX_E6D4CDADCF8DA6E6 (id_participant), PRIMARY KEY(id_sortie, id_participant)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville (id_ville INT AUTO_INCREMENT NOT NULL, nom VARCHAR(30) NOT NULL, code_postal VARCHAR(10) NOT NULL, PRIMARY KEY(id_ville)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lieu ADD CONSTRAINT FK_2F577D59AD4698F3 FOREIGN KEY (id_ville) REFERENCES ville (id_ville)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11BB3EDDFC FOREIGN KEY (id_campus) REFERENCES campus (id_campus)');
        $this->addSql('ALTER TABLE participant_sortie ADD CONSTRAINT FK_8E436D73CF8DA6E6 FOREIGN KEY (id_participant) REFERENCES participant (id_participant)');
        $this->addSql('ALTER TABLE participant_sortie ADD CONSTRAINT FK_8E436D731A08661F FOREIGN KEY (id_sortie) REFERENCES sortie (id_sortie)');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2CF8DA6E6 FOREIGN KEY (id_participant) REFERENCES participant (id_participant)');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2A477615B FOREIGN KEY (id_lieu) REFERENCES lieu (id_lieu)');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2DEEAEB60 FOREIGN KEY (id_etat) REFERENCES etat (id_etat)');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2BB3EDDFC FOREIGN KEY (id_campus) REFERENCES campus (id_campus)');
        $this->addSql('ALTER TABLE sortie_participant ADD CONSTRAINT FK_E6D4CDAD1A08661F FOREIGN KEY (id_sortie) REFERENCES sortie (id_sortie)');
        $this->addSql('ALTER TABLE sortie_participant ADD CONSTRAINT FK_E6D4CDADCF8DA6E6 FOREIGN KEY (id_participant) REFERENCES participant (id_participant)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11BB3EDDFC');
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F2BB3EDDFC');
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F2DEEAEB60');
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F2A477615B');
        $this->addSql('ALTER TABLE participant_sortie DROP FOREIGN KEY FK_8E436D73CF8DA6E6');
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F2CF8DA6E6');
        $this->addSql('ALTER TABLE sortie_participant DROP FOREIGN KEY FK_E6D4CDADCF8DA6E6');
        $this->addSql('ALTER TABLE participant_sortie DROP FOREIGN KEY FK_8E436D731A08661F');
        $this->addSql('ALTER TABLE sortie_participant DROP FOREIGN KEY FK_E6D4CDAD1A08661F');
        $this->addSql('ALTER TABLE lieu DROP FOREIGN KEY FK_2F577D59AD4698F3');
        $this->addSql('DROP TABLE campus');
        $this->addSql('DROP TABLE etat');
        $this->addSql('DROP TABLE lieu');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE participant_sortie');
        $this->addSql('DROP TABLE sortie');
        $this->addSql('DROP TABLE sortie_participant');
        $this->addSql('DROP TABLE ville');
    }
}
