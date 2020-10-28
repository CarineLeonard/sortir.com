<?php
namespace App\EventSubscriber;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

class EasyAdminSubscriber implements EventSubscriberInterface {

    private $slugger ;
    private $encoder ;

    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $encoder)
    {
        $this->slugger = $slugger;
        $this->encoder = $encoder;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setParticipant'],
            BeforeEntityUpdatedEvent::class => ['updateParticipant'],
        ];
    }

    /*// pour delete un participant
    public function deleteEntity(BeforeEntityDeletedEvent $event) {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Participant) {
            $sorties = $this->slugger->slug($entity->getSorties());
            for ($s=0; $s< count($sorties); $s++) {

            }

        } elseif ($entity instanceof Campus) {
            // "ancien campus" en nom de campus : existant ou create ! pour les participants !
            // mais pb / page accueil : remonte ! ou alors bascule sur "Nantes" / ou suppression ?

        } elseif ($entity instanceof Sortie) {


        } elseif ($entity instanceof Lieu) {


        } elseif ($entity instanceof Ville) {


        } else {
            return;
        }


    } */


    // pour create personnalisé sur easyadmin
    public function setParticipant(BeforeEntityPersistedEvent $event) {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Participant)) {
            return;
        }
        $motPasse = $this->slugger->slug($entity->getNewPassword());
        $entity->setMotPasse($this->encoder->encodePassword($entity, $motPasse));
    }

    // pour update perso sur easy admin
    public function updateParticipant(BeforeEntityUpdatedEvent $event) {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Participant)) {
            return;
        }
        if ($entity->getNewPassword()) {
            $motPasse = $this->slugger->slug($entity->getNewPassword());
            $entity->setMotPasse($this->encoder->encodePassword($entity, $motPasse));
        }
    }


}