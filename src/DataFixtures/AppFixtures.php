<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Participant;
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

        //Campus
        for ($i = 0; $i < 12; $i++)
        {
            $campus = new Campus();
            $campus->setNom($faker->city);
            $manager->persist($campus);
            $userCampus = $campus;
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
            $participant->setMail(($faker->userName).'@campus-eni.fr');
            $participant->setPseudo($faker->userName);
            $participant->setTelephone($faker->mobileNumber);
            $participant->setMotPasse($this->encoder->encodePassword($participant,($faker->password)));
            $participant->setAdministrateur($faker->boolean);
            $participant->setActif($faker->boolean);
            $participant->setCampus( $nosCampus[random_int(1, count($nosCampus))]);

            $manager->persist($participant);
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
