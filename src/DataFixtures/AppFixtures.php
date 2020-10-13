<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // langue = FR
        $faker = \Faker\Factory::create('fr_FR');

        //Campus : si on en veut plus que les 8 connus
        for ($i = 0; $i < 12; $i++)
        {
            $campus = new Campus();
            $campus->setNom($faker->city);
            $manager->persist($campus);
        }
        $manager->flush();
        $campusRepository = $manager->getRepository(Campus::class);
        $nosCampus = $campusRepository->findAll();

        // 20 personnes
        for ($i = 0; $i < 20; $i++)
        {
            $participant = new Participant();
            $participant->setNom($faker->lastName);
            $participant->setPrenom($faker-> firstName);
            $participant->setMail(($faker->unique()->userName).'@campus-eni.fr');
            $participant->setPseudo($faker->unique()->userName);
            $participant->setTelephone($faker->mobileNumber);
            $participant->setMotPasse($this->encoder->encodePassword($participant,($faker->password)));
            $participant->setAdministrateur($faker->boolean);
            $participant->setActif($faker->boolean);
            $participant->setCampus( $nosCampus[random_int(1, count($nosCampus)-1)]);

            $manager->persist($participant);
        }
        $manager->flush();
        $participantRepository = $manager->getRepository(Participant::class);
        $nosParticipant = $participantRepository->findAll();

        // 20 villes
        for ($i = 0; $i < 20; $i++)
        {
            $ville = new Ville();
            $ville->setNom($faker->city);
            $ville->setCodePostal($faker->postcode);
            $manager->persist($ville);
        }
        $manager->flush();
        $villeRepository = $manager->getRepository(Ville::class);
        $nosVilles = $villeRepository->findAll();

        // 20 lieux
        for ($i = 0; $i < 20; $i++)
        {
            $lieu = new Lieu();
            $lieu->setNomLieu($faker->city);
            $lieu->setRue($faker->streetAddress);
            $lieu->setLatitude($faker->latitude);
            $lieu->setLongitude($faker->longitude);
            $lieu->setVille( $nosVilles[random_int(1, count($nosVilles)-1)]);
            $manager->persist($lieu);
        }
        $manager->flush();
        $lieuRepository = $manager->getRepository(Lieu::class);
        $nosLieu = $lieuRepository->findAll();

        $etatRepository = $manager->getRepository(Etat::class);
        $nosEtat = $etatRepository->findAll();



        // 20 sorties
        for ($i = 0; $i < 20; $i++)
        {
            $sortie = new Sortie();
            $sortie->setNom(substr($faker->sentence($nbWords = 2, $variableNbWords = true), 0, 20));
            $sortie->setDateHeureDebut($faker->dateTimeBetween($startDate = '-1 months', $endDate = '+3 months', $timezone = 'Europe/Paris'));
            $sortie->setDuree($faker->numberBetween($min = 1, $max = 288));
            do {
                $sortie->setDateLimiteInscription($faker->dateTimeBetween($startDate = '-1 months', $endDate = '+3 months', $timezone = 'Europe/Paris'));
            } while ($sortie->getDateHeureDebut() > $sortie->getDateLimiteInscription() );

            $sortie->setNbinscriptionsMax($faker->numberBetween($min = 6, $max = 20));
            $sortie->setInfosSortie($faker->text($maxNbChars = 400));
            $sortie->setEtat( $nosEtat[random_int(1, count($nosEtat)-1)]);
            $sortie->setSiteOrganisateur( $nosCampus[random_int(1, count($nosCampus)-1)]);
            $sortie->setLieu( $nosLieu[random_int(1, count($nosLieu)-1)]);
            $sortie->setOrganisateur( $nosParticipant[random_int(1, count($nosParticipant)-1)]);
            $i=0;
            $j = $faker->numberBetween($min = 1, $max = $sortie->getNbinscriptionsMax()) ;
            do {
                $sortie->addParticipants($participantRepository->find($nosParticipant[random_int(1, count($nosParticipant)-1)]));
                $i++;
            } while ($i <= $j) ;

            $manager->persist($sortie);
        }
        $manager->flush();

    }



    // Cette partie permet de s'assurer que BaseFixtures est exécutée en premier.
    // Cela permet de disposer d'un campus (déjà créé dans BaseFixtures) grâce à addReference et getReference.
    // Il faut implémenter DependentFixtureInterface pour que ça fonctionne.
    // https://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html#sharing-objects-between-fixtures
   public function getDependencies()
    {
        return array(
            BaseFixtures::class,
        );
    }
}
