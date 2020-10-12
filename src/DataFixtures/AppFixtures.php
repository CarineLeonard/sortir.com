<?php

namespace App\DataFixtures;

use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // On configure dans quelles langues nous voulons nos données
        $faker = Faker\Factory::create('fr_FR');


        // on créé 10 personnes : regarder les types !!
        for ($i = 0; $i < 10; $i++) {
            $participant = new Participant();
            $participant->setNom($faker->name);
            $participant->setPrenom($faker-> name);
            $participant->setTelephone($faker-> serviceNumber );
            $participant->setMail($faker->email);
            $participant->setPassword($faker->email);
            $participant->setAdministrateur($faker->boolean );
            $participant->setActif($faker->boolean );
            $participant->setCampus($faker->number);

            $participant->persist($participant);
        }

        $manager->flush();
    }
}
