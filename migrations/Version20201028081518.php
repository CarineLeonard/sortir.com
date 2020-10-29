<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201028081518 extends AbstractMigration
{

    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO etat (libelle) VALUES ("créée")');
        $this->addSql('INSERT INTO etat (libelle) VALUES ("ouverte")');
        $this->addSql('INSERT INTO etat (libelle) VALUES ("clôturée")');
        $this->addSql('INSERT INTO etat (libelle) VALUES ("en cours")');
        $this->addSql('INSERT INTO etat (libelle) VALUES ("passée")');
        $this->addSql('INSERT INTO etat (libelle) VALUES ("annulée")');
        $this->addSql('INSERT INTO etat (libelle) VALUES ("historisée")');
        $this->addSql('INSERT INTO campus (nom) VALUES ("Nantes Faraday")');
        $this->addSql('INSERT INTO participant (nom, prenom, telephone, mail, mot_passe, administrateur, actif, pseudo, id_campus) 
                VALUES ("admin", "admin", "0612345678", "admin@campus-eni.fr", "$2y$13$OUsyrZL2tOa7DkNd9YSMpufHbspy8XkFJq1ywa3rFoMAKrOaR9z5O",
                1, 1, "admin", (SELECT id_campus FROM campus WHERE nom like "Nantes Faraday"))');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
