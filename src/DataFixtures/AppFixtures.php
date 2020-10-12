<?php

namespace App\DataFixtures;

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
        // On configure dans quelles langues nous voulons nos données
        $faker = \Faker\Factory::create('fr_FR');


        // on créé 10 personnes : regarder les types !!
        for ($i = 0; $i < 10; $i++) {
            $participant = new Participant();
            $participant->setNom($faker->name);
            $participant->setPrenom($faker-> name);

//            $participant->setTelephone($faker->serviceNumber );
// Champ commenté car faker génère des numéros trop longs (espaces entre paires de chiffres)

            $participant->setMail('mail'.$i.'@20CharsMax.com');
//            $participant->setMail($faker->email);
// Champ commenté car faker génère des mails plus long que les 20 chars définis sur l'entity.
// (ils prévoyaient peut-être que toutes les adresses soient en @campus-eni.fr et de ne pas stocker cette partie ? Ou bien ? ;))

            $participant->setMotPasse($this->encoder->encodePassword($participant,'passe'));
            $participant->setAdministrateur($faker->boolean );
            $participant->setActif($faker->boolean );

// Pour le campus j'ai créé une référence dans BaseFixtures vers un campus déjà enregistré, on peut peut-être faire différemment ?
// Lien vers la doc dans le commentaire suivant.
            $participant->setCampus($this->getReference(BaseFixtures::CAMPUS_REFERENCE));

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
