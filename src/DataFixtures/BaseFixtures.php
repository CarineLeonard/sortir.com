<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BaseFixtures extends Fixture
{
    public const CAMPUS_REFERENCE = 'campus';

    private $libellesCampus = [
        'Quimper',
        'Rennes',
        'Nantes Faraday',
        'Niort',
        'Laval',
        'Mans',
        'Anger',
        'La Roche-sur-Yon'
    ];

    private $libellesEtats = [
        'créée',
        'ouverte',
        'clôturée',
        'en cours',
        'passée',
        'annulée',
        'historisée'
    ];

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
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
        $this->addReference(self::CAMPUS_REFERENCE, $userCampus);

        //Etats
        foreach ($this->libellesEtats as $libelle)
        {
            $etat = new Etat();
            $etat->setLibelle($libelle);
            $manager->persist($etat);
        }
        $manager->flush();

        //Participants
        //user
        $participant = new Participant();
        $participant->setNom('user');
        $participant->setPrenom('name');
        $participant->setTelephone('0654321987');
        $participant->setMail('user@mail.com');
        $participant->setMotPasse($this->encoder->encodePassword($participant,'Passw0rd'));
        $participant->setAdministrateur(false);
        $participant->setActif(true);
        $participant->setCampus($userCampus);
        $participant-> setPseudo('user');
        $participant->setImageFilename('uploads/images/profilDefault.jpg');
        $manager->persist($participant);

        //admin
        $participant = new Participant();
        $participant->setNom('admin');
        $participant->setPrenom('name');
        $participant->setTelephone('0654321987');
        $participant->setMail('admin@mail.com');
        $participant->setMotPasse($this->encoder->encodePassword($participant,'Passw0rd'));
        $participant->setAdministrateur(true);
        $participant->setActif(true);
        $participant->setCampus($userCampus);
        $participant-> setPseudo('admin');
        $participant->setImageFilename('profilDefault.jpg');
        $manager->persist($participant);

        $manager->flush();
    }
}
