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

class FakerFixtures extends Fixture implements DependentFixtureInterface
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
        /*for ($i = 0; $i < 12; $i++)
        {
            $campus = new Campus();
            $campus->setNom($faker->city);
            $manager->persist($campus);
        }
        $manager->flush();*/
        $campusRepository = $manager->getRepository(Campus::class);
        $nosCampus = $campusRepository->findAll();

        // 30 personnes
        for ($i = 0; $i < 30; $i++)
        {
            $participant = new Participant();
            $participant->setNom($faker->lastName);
            $participant->setPrenom($faker-> firstName);
            $participant->setMail(($faker->unique()->userName).'@campus-eni.fr');
            $participant->setPseudo($faker->unique()->userName);
            $participant->setTelephone($faker->mobileNumber);
            $participant->setMotPasse($this->encoder->encodePassword($participant,'Passw0rd'));
            $participant->setAdministrateur($faker->boolean);
            $participant->setActif($faker->boolean);
            $participant->setCampus( $nosCampus[random_int(0, count($nosCampus)-1)]);

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
            $lieu->setNom($faker->city);
            $lieu->setRue($faker->streetAddress);
            $lieu->setLatitude($faker->latitude);
            $lieu->setLongitude($faker->longitude);
            $lieu->setVille( $nosVilles[random_int(0, count($nosVilles)-1)]);
            $manager->persist($lieu);
        }
        $manager->flush();
        $lieuRepository = $manager->getRepository(Lieu::class);
        $nosLieu = $lieuRepository->findAll();

        $etatRepository = $manager->getRepository(Etat::class);
        $nosEtat = $etatRepository->findAll();



        // 40 sorties
        for ($i = 0; $i < 20; $i++)
        {
            $sortie = new Sortie();
            $sortie->setNom(substr($faker->word, 0, 20));
            // ok !
            $sortie->setDateHeureDebut($faker->dateTimeBetween($startDate = '-1 month', $endDate = '+6 months', $timezone = null));
            $sortie->setDuree($faker->numberBetween($min = 30, $max = 450));
            // ok ??
            $date = date_create($sortie->getDateHeureDebut()->format("Y-m-d"));
            $date2 = date_modify($date,"-1 month");
            $sortie->setDateLimiteInscription($faker->dateTimeBetween($startDate = $date2,
                $endDate = ($sortie->getDateHeureDebut()), $timezone = 'Europe/Paris'));

            $sortie->setNbinscriptionsMax($faker->numberBetween($min = 6, $max = 20));
            $sortie->setInfosSortie($faker->text($maxNbChars = 400));
            // à modifier !!
            if ($sortie->getDateHeureDebut()>new \DateTime('now'))
            {
                if (($sortie->getDateLimiteInscription())>new \DateTime('now'))
                {
                    $sortie->setEtat( $nosEtat[random_int(0,1)]);
                } else {
                    $sortie->setEtat( $nosEtat[2]);
                }
            } else {
                $sortie->setEtat( $nosEtat[random_int(3,5)]);
            }

            $sortie->setSiteOrganisateur( $nosCampus[random_int(0, count($nosCampus)-1)]);
            $sortie->setLieu( $nosLieu[random_int(0, count($nosLieu)-1)]);
            $sortie->setOrganisateur( $nosParticipant[random_int(0, count($nosParticipant)-1)]);
            // ok ??
            $j = $faker->numberBetween($min = 1, $max = ($sortie->getNbinscriptionsMax())) ;
            for ($i=0; $i<=$j; $i++)
            {
                if ($sortie->getEtat() !== $nosEtat[0]) {
                    $sortie->addParticipants($participantRepository->find($nosParticipant[random_int(0, count($nosParticipant)-1)]));
                }
            }
            if (count($sortie->getParticipants()) == $sortie->getNbinscriptionsMax()) {
                $sortie->setEtat($nosEtat[2]);
            }
            $manager->persist($sortie);
        }
        $manager->flush();

        // sorties persos
        $sortie = new Sortie();
        $sortie->setNom('Ma sortie');
        $sortie->setDateHeureDebut(new \DateTime('2020-10-30'));
        $sortie->setDuree(160);
        $sortie->setDateLimiteInscription(new \DateTime('2020-10-27'));
        $sortie->setNbinscriptionsMax(8);
        $sortie->setInfosSortie('Les infos sont importantes, alors les voici en détail : eer er er ezr zer  retrt ret retret ert ret ert ert er tr ter
        trtretretr tr e tr  tr t rt rt tr t e te rtetret');
        $sortie->setEtat($nosEtat[1]);
        $sortie->setSiteOrganisateur($nosCampus[2]);
        $sortie->setLieu($nosLieu[2]);
        $sortie->setOrganisateur($nosParticipant[1]);

        $manager->persist($sortie);

        $sortie2 = new Sortie();
        $sortie2->setNom('Ma sortie2');
        $sortie2->setDateHeureDebut(new \DateTime('2020-11-31'));
        $sortie2->setDuree(160);
        $sortie2->setDateLimiteInscription(new \DateTime('2020-11-30'));
        $sortie2->setNbinscriptionsMax(16);
        $sortie2->setInfosSortie('Les infos sont importantes, alors les voici en détail : eer er er ezr zer  retrt ret retret ert ret ert ert er tr ter
        trtretretr tr e tr  tr t rt rt tr t e te rtetret');
        $sortie2->setEtat($nosEtat[0]);
        $sortie2->setSiteOrganisateur($nosCampus[3]);
        $sortie2->setLieu($nosLieu[6]);
        $sortie2->setOrganisateur($nosParticipant[1]);

        $manager->persist($sortie2);

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
