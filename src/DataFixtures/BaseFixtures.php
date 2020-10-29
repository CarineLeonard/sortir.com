<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BaseFixtures extends Fixture implements FixtureGroupInterface
{
    private $libellesEtats = [
        'créée',
        'ouverte',
        'clôturée',
        'en cours',
        'passée',
        'annulée',
        'historisée'
    ];

    private $libellesCampus = [
//        'Quimper',
//        'Rennes',
        'Nantes Faraday',
//        'Niort',
//        'Laval',
//        'Mans',
//        'Anger',
//        'La Roche-sur-Yon'
    ];

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public static function getGroups(): array
    {
        return ['install', 'faker'];
    }

    public function load(ObjectManager $manager)
    {
        //Etats
        foreach ($this->libellesEtats as $libelle)
        {
            $etat = new Etat();
            $etat->setLibelle($libelle);
            $manager->persist($etat);
        }
        $manager->flush();

        //Campus
        $userCampus = new Campus();
        foreach ($this->libellesCampus as $libelle)
        {
            $campus = new Campus();
            $campus->setNom($libelle);
            $manager->persist($campus);
            $userCampus = $campus;
        }
        $manager->flush();


        //Participants
        //user
//        $participant = new Participant();
//        $participant->setNom('user');
//        $participant->setPrenom('name');
//        $participant->setTelephone('0654321987');
//        $participant->setMail('user@campus-eni.fr');
//        $participant->setMotPasse($this->encoder->encodePassword($participant,'Passw0rd'));
//        $participant->setAdministrateur(false);
//        $participant->setActif(true);
//        $participant->setCampus($userCampus);
//        $participant-> setPseudo('user');
//        $manager->persist($participant);

        //admin : dans migrations
        $participant = new Participant();
        $participant->setNom('admin');
        $participant->setPrenom('name');
        $participant->setTelephone('0654321987');
        $participant->setMail('admin@campus-eni.fr');
        $participant->setMotPasse($this->encoder->encodePassword($participant,'Passw0rd'));
        $participant->setAdministrateur(true);
        $participant->setActif(true);
        $participant->setCampus($userCampus);
        $participant-> setPseudo('admin');
        $manager->persist($participant);

        $manager->flush();
    }
}
